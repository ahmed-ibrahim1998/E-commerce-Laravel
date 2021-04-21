<?php

namespace App\Notifications;

use App\Models\Vendor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VendorCreated extends Notification
{
    use Queueable;  ///   queue  هذا طابور بيخزن
    ///  الاف رسائل الاشعارات لارسالها الي المستخدمين ولكن علشان السرفر مايضربش معانا بيرسل واحد واحد بنستخد جدول معاه اسمه jobs
    ///

    public $vendor;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Vendor $vendor)
    {
        $this -> vendor = $vendor;// التاجر اللي هبعتله الاشعار
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)  // هذه داله بترسل الاشعار عبر الميل او رساله فون او في الداتا بيز او عن طريق حاجه اسمها slack
    {
        return ['mail'];
//      return ['mail','sms','database','slack'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $subject = sprintf('%s: لقد تم انشاء حسابكم في موقع الامامي %s!', config('app.name'), 'ahmed');
        $greeting = sprintf('مرحبا %s!', $notifiable->name);  // $notifiable->name  الشخص اللي هيتبعتله

        return (new MailMessage)
            ->subject($subject)
            ->greeting($greeting)
            ->salutation('Yours Faithfully')
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
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
            // بستخدمها في حاله لو هبعت اشعار عن طريق الداتا بيز فوق فبستخدم هذه الداله علشان اخزن فيها العمود اللي هخزن فيه
        ];
    }
}
