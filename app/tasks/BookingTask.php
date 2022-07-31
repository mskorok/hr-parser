<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 08.12.17
 * Time: 17:09
 */

namespace Tasks;

use App\Constants\AclRoles;
use App\Constants\Limits;
use App\Constants\Services;
use App\Model\Booking;
use App\Model\BookingItems;
use App\Model\Product;
use App\Model\User;
use Phalcon\Cli\Task;
use Phalcon\DiInterface;
use Phalcon\Queue\Beanstalk;

/**
 * {@inheritDoc}
 */
class BookingTask extends Task
{
    public function bookingAction()
    {
        $time = new \DateTime();
        $time->modify('-1 month');
        $timeString = $time->format('Y-m-d H:i:s');
        $bookings = Booking::find([
            'bookingDate >= "'. $timeString . '"',
            'limit' => Limits::SEARCH_LIMIT,
        ]);
        $setting = $this->getDI()->get(Services::CONFIG);
        $partAmount = $setting->booking->partAmount;
        foreach ($bookings as $booking) {
            /** @var \Phalcon\Mvc\Model\Resultset\Simple $items */
            $items =  $booking->getBookingItems();
            $price = 0;
            /** @var \App\Model\BookingItems $item */
            foreach ($items as $item) {
                $price += $item->getFinalPrice();
            }
            /** @var \Phalcon\Mvc\Model\Resultset\Simple $payments */
            $payments = $booking->getPayment();
            $amount = 0;
            /** @var \App\Model\Payment $payment */
            foreach ($payments as $payment) {
                if ($payment->getStatus() === 'confirmed') {
                    $amount += $payment->getAmount();
                }
            }
            if ((int) $price < (int) $partAmount && $booking->getPaymentType() === 'part') {
                $booking->setPaymentType('full');
                $booking->save();
            }
            if ($booking->getPaymentType() === 'part' && $booking->getStatus() !== 'rejected') {
                if ($amount < $price/3) {
                    $booking->setStatus('rejected');
                    $booking->save();
                }
            }

            if ($amount < $price && $this->expiredDate($booking) && $booking->getStatus() !== 'rejected') {
                $booking->setStatus('rejected');
                $booking->save();
            }

            if ($amount >= $price  && $booking->getStatus() !== 'confirmed') {
                $booking->setStatus('confirmed');
                $booking->save();
            }
        }
    }

    public function reminderBeforeAction()
    {
        foreach (\App\Constants\Message::REMIND_BEFORE as $number) {
            $time = new \DateTime();
            $time->modify('-' . $number . ' days');
            $timeString = $time->format('Y-m-d');
            $this->putBeforeStartDatesJob($timeString);
        }
    }

    public function reminderStartAction()
    {
        foreach (\App\Constants\Message::REMIND_BEFORE as $number) {
            $time = new \DateTime();
            $time->modify('-' . $number . ' minutes');//-5min
            $timeStringStart = $time->format('Y-m-d H:i:s');
            $number +=3;
            $time = new \DateTime();
            $time->modify('-' . $number . ' minutes');//-8min
            $timeStringEnd = $time->format('Y-m-d H:i:s');
            $this->putBeforeStartJob($timeStringStart, $timeStringEnd);
        }
    }

    public function reminderAfterAction()
    {
        foreach (\App\Constants\Message::REMIND_AFTER as $number) {
            $time = new \DateTime();
            $time->modify('+' . $number . ' days');
            $timeString = $time->format('Y-m-d');
            $this->putAfterEndStartJob($timeString);
        }
    }

    public function monthInvoices()
    {
        $time = new \DateTime();
        $time->modify('-1 month');
        $timeString = $time->format('Y-m-d');
        $this->putMonthInvoiceJob($timeString);
    }


    protected function expiredDate(Booking $booking)
    {
        $setting = $this->getDI()->get(Services::CONFIG);
        $dl = $setting->booking->deedline;
        $date = $booking->getBookingDate();
        return strtotime('+'.$dl.' day', strtotime($date)) > time();
    }

    protected function putBeforeStartDatesJob($timeString)
    {
        /** @var \Phalcon\Mvc\Model\Resultset\Simple $items */
        $items = BookingItems::find([
            'startDate LIKE "'. $timeString . '%"',
            'limit' => Limits::SEARCH_LIMIT,
        ]);
        /** @var \Phalcon\DiInterface $di */
        $di = $this->getDI();
        if (!($di instanceof DiInterface)) {
            throw new \RuntimeException('DI not found');
        }
        /** @var Beanstalk $queue */
        $queue = $this->getDI()->get(Services::QUEUE);
        /** @var BookingItems $item */
        foreach ($items as $item) {
            /** @var Booking $booking */
            $booking = $item->getBooking();
            /** @var User $user */
            $user = $booking->getUser();
            $body = [
                'recipient' => $user->getFirstName().' '.$user->getLastName(),
                'to' => $user->getEmail(),
                'booking' => $booking,
                'item' => $item,
                'product' => $item->getProduct(),
            ];
            $job = new \App\Jobs\BeforeStartDatesLetterJob($queue, $user->getId(), $body);
            $queue->put($job);
        }
    }

    protected function putAfterEndStartJob($timeString)
    {
        /** @var \Phalcon\Mvc\Model\Resultset\Simple $items */
        $items = BookingItems::find([
            'endDate LIKE "'. $timeString . '%"',
            'limit' => Limits::SEARCH_LIMIT,
        ]);
        /** @var \Phalcon\DiInterface $di */
        $di = $this->getDI();
        if (!($di instanceof DiInterface)) {
            throw new \RuntimeException('DI not found');
        }
        /** @var Beanstalk $queue */
        $queue = $this->getDI()->get(Services::QUEUE);
        /** @var BookingItems $item */
        foreach ($items as $item) {
            /** @var Booking $booking */
            $booking = $item->getBooking();
            /** @var User $user */
            $user = $booking->getUser();
            $body = [
                'recipient' => $user->getFirstName().' '.$user->getLastName(),
                'to' => $user->getEmail(),
                'booking' => $booking,
                'item' => $item,
                'product' => $item->getProduct(),
            ];
            $job = new \App\Jobs\AfterEndLetterJob($queue, $user->getId(), $body);
            $queue->put($job);
        }
    }

    protected function putBeforeStartJob($timeStringStart, $timeStringEnd)
    {
        /** @var \Phalcon\Mvc\Model\Resultset\Simple $items */
        $items = BookingItems::find([
            'startDate > '. $timeStringEnd . '"',
            'startDate < '. $timeStringStart . '"',
            'limit' => Limits::SEARCH_LIMIT,
        ]);
        if ($items->count() === 0) {
            return false;
        }
        /** @var \Phalcon\DiInterface $di */
        $di = $this->getDI();
        if (!($di instanceof DiInterface)) {
            throw new \RuntimeException('DI not found');
        }
        /** @var Beanstalk $queue */
        $queue = $this->getDI()->get(Services::QUEUE);
        /** @var BookingItems $item */
        foreach ($items as $item) {
            /** @var Booking $booking */
            $booking = $item->getBooking();
            /** @var User $user */
            $user = $booking->getUser();
            $body = [
                'recipient' => $user->getFirstName().' '.$user->getLastName(),
                'to' => $user->getEmail(),
                'booking' => $booking,
                'item' => $item,
                'product' => $item->getProduct(),
            ];
            $job = new \App\Jobs\BeforeStartLetterJob($queue, $user->getId(), $body);
            $queue->put($job);
        }
        return true;
    }

    protected function putMonthInvoiceJob($timeString)
    {
        /** @var \Phalcon\Mvc\Model\Resultset\Simple $items */
        $items = BookingItems::find([
            'startDate > '. $timeString . '%"',
            'limit' => Limits::SEARCH_LIMIT,
        ]);
        if ($items->count() === 0) {
            return false;
        }
        /** @var \Phalcon\DiInterface $di */
        $di = $this->getDI();
        if (!($di instanceof DiInterface)) {
            throw new \RuntimeException('DI not found');
        }
        /** @var Beanstalk $queue */
        $queue = $this->getDI()->get(Services::QUEUE);
        /** @var BookingItems $item */
        foreach ($items as $item) {
            /** @var Booking $booking */
            $booking = $item->getBooking();
            /** @var User $user */
            $user = $booking->getUser();
            /** @var Product $product */
            $product = $item->getProduct();
            /** @var User $provider */
            $provider = $product->getUser();
            if ($provider->getRole() !== AclRoles::JOURDAY_PROVIDER) {
                $body = [
                    'recipient' => $user->getFirstName().' '.$user->getLastName(),
                    'to' => $user->getEmail(),
                    'booking' => $booking,
                    'item' => $item,
                    'product' => $item->getProduct(),
                ];
                $job = new \App\Jobs\MonthInvoicesLetterJob($queue, $user->getId(), $body);
                $queue->put($job);
            }
        }
        return true;
    }
}
