<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CategoryCreate extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $category;

    /**
     * CategoryCreate constructor.
     *
     * @param $category
     */
    public function __construct($category)
    {
        $this->category = $category;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.category_create')
            ->with('category', $this->category);
    }
}