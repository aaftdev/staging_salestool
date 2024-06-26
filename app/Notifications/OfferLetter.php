<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class OfferLetter extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject($this->data['name'] . ' | Offer for Admission | ' . $this->data['course'])
            ->greeting('Hello ' . $this->data['name'] . ',')
            ->line('We are pleased to inform you that you have been selected for an offer of admission to the ' . $this->data['course'] . ', ' . Carbon::parse($this->data['commence_date'])->format("M'Y") . ' at AAFT.')
            ->line('We congratulate you on your selection, and are enclosing herewith a soft copy of the admission offer letter.')
            ->line('You are required to pay the admission fees for the mentioned program on or before the mentioned deadline to confirm your candidature.')
            ->line(new HtmlString('<b>Important Deadlines and Details: </b>(Complete breakup of fees, deadlines and fee remittance guidelines are mentioned in the attached offer letter.)'))
            ->line(new HtmlString('<b>1) Admission Fee Payment Deadline - Rs. ' . $this->data['admission_fee'] . ' : </b>' . $this->data['admission_fee_date']))
            ->line(new HtmlString('<b>To make the fee payment, please use the payment link given below</b>'));
        if ($this->data["scholorship"] > 0) {
            $mail->line(new HtmlString('<b>You are entitled to a Merit-based Scholarship of INR ' . round($this->data["scholorship"], 1) . ' which has been adjusted against your course fee.</b>'));
        }
        $mail->action('Click Here To Pay Fees', route('user.payment', $this->data['short_link']))
            ->line(new HtmlString('Also attached with this email is the <b>Program Brochure for ' . $this->data['course'] . ' and details for Loan assistance</b>. With over half the course coverage led by industry, the uniquely designed curriculum is what makes this program endorsed by the industry.'))
            ->line(new HtmlString('Should you be in need of any assistance or guidance, please feel free to reach out to the Admissions Team. Alternatively, you can also write to the Admissions Director – Mrs. Albeena Abbas at  <a href="mailto:director@aaftonline.com">director@aaftonline.com</a>'))
            ->line("We are excited to have you on board and believe that AAFT's " . $this->data['course'] . " would be a rewarding learning experience for you and a Game Changer for your career!")
            ->line(new HtmlString("Please note that all rights for the admission in the program are reserved with the Program Director's Office. Any decision regarding the same taken by the program director's office will be final and binding. <b>The admission offered to you is subject to the availability of seats in the program.</b>"))
            ->line('Congratulations again. We look forward to seeing you soon.');

        $mail->cc('admission-support@aaftonline.com');
        foreach ($this->data['attachments'] as $filePath => $fileParam) {
            $mail->attach($filePath, $fileParam);
        }
        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
