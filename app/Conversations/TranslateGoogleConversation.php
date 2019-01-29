<?php
/**
 * Created by PhpStorm.
 * User: pay
 * Date: 28/01/2019
 * Time: 10:07
 */

namespace App\Conversations;


use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Stichoza\GoogleTranslate\GoogleTranslate;

class TranslateGoogleConversation extends Conversation
{
    protected $languageFrom;
    protected $languageTo;

    public function askFromLanguageTranslate()
    {
        $question = Question::create('Chọn ngôn ngữ nhập vào')->addButtons([
            Button::create('Tiếng Việt')->value('vi'),
            Button::create('Tiếng Anh')->value('en'),
            Button::create('Tiếng Đức')->value('de'),
            Button::create('Tiếng Trung')->value('zh-TW'),
            Button::create('Tiếng Hàn')->value('ko'),
            Button::create('Tiếng Nhật')->value('ja'),
        ]);

        return $question;
    }

    public function askToLanguageTranslate()
    {
        $question = Question::create('Chọn ngôn ngữ dịch ra')->addButtons([
            Button::create('Tiếng Việt')->value('vi'),
            Button::create('Tiếng Anh')->value('en'),
            Button::create('Tiếng Đức')->value('de'),
            Button::create('Tiếng Trung')->value('zh-TW'),
            Button::create('Tiếng Hàn')->value('ko'),
            Button::create('Tiếng Nhật')->value('ja'),
        ]);

        return $question;
    }

    public function askTextTranslate()
    {
        $this->ask('Nhập anything cần dịch?', function (Answer $answer) {

            $tr = new GoogleTranslate(); // Translates to 'en' from auto-detected language by default
            $tr->setSource($this->languageFrom); // Translate from English
            $tr->setSource(); // Detect language automatically
            $tr->setTarget($this->languageTo); // Translate to Vietnam.

            $this->say($tr->translate($answer->getText()));

            $this->askTextTranslate();
        });
    }

    public function run()
    {
        $this->ask($this->askFromLanguageTranslate(), function (Answer $answer) {

            $this->languageFrom = $answer->getText();
            $this->say($this->languageFrom);

            $this->ask($this->askToLanguageTranslate(), function (Answer $answer) {

                $this->languageTo = $answer->getText();
                $this->say($this->languageTo);

                $this->askTextTranslate();
            });
        });
    }
}