<?php

declare(strict_types=1);

namespace Views;

class SignupView extends ABCView
{
    /**
     * @var string $name Name of user from the post
     */
    protected string $name = '';

    /**
     * @var string $email Email of user from the post
     */
    protected string $email = '';

    /**
     * @var string $password Pure password form the post
     */
    protected string $password = '';

    /**
     * @var string $passwordRepeat Pure password repeat from the post
     */
    protected string $passwordRepeat = '';

    /**
     * @var string $confirmPD Value of check box in the post
     */
    protected string $confirmPD = '';

    /**
     * signup.js checkName function analog. Used for name validation
     *
     * @return bool True if name is valid else false
     */
    public function checkName(): bool
    {
        if ($this->name === '' || strlen($this->name) > 64) return false;
        // Like in JS code there is no 'islpha' string method
        // that's why we need to -pain- use regexp arrange.
        // By some reason russian arrange is not working
        $pattern = '/[A-Za-z]|[АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЬьЭэЮюЯя]/';
        foreach (preg_split('/ /', $this->name) as $word) {
            if ($word === '') {
                return false;
            }
            for ($i = 0; $i < strlen($word); $i++) {
                if (!preg_match($pattern, $word[$i])) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * signup.js checkEmail function analog. Used for email validation.
     * Real validation only on services (email is real if we could send a message)
     *
     * @return bool True if email probably real else false
     */
    public function checkEmail(): bool
    {
        if ($this->email === ''
            || strlen($this->email) > 320
            || !str_contains($this->email, '@')
            || !str_contains($this->email, '.')) {
            return false;
        }
        $dog = strpos($this->email, '@') ?: -1;
        $dot = strpos($this->email, '.', $dog) ?: -1;
        return $dog > 0 && $dot > $dog && $dot !== (strlen($this->email) - 1);
    }

    /**
     * signup.js checkPassword function analog. Used for password minimal strong
     * validation
     *
     * @return bool True if password is probably not peace of shit else false
     */
    public function checkPassword(): bool
    {
        if ($this->password !== $this->passwordRepeat
            || strlen($this->password) < 6
            || !preg_match('/[0-9]/', $this->password)
            || !preg_match('/[a-z]/', $this->password)
            || !preg_match('/[A-Z]/', $this->password)) {
            return false;
        }
        // Note: there could be symfony validation if programmer (me) would repair init error
        // NotCompromisedPasswordValidator - cannot repair
        //     'Call to a member function buildViolation() on null'
        // Seems like there internal kernel error. May be I just cannot to init it
        return true;
    }

    /**
     * Function collect post data and sanitize it. Must be called only
     * on POST method
     */
    public function collect()
    {
        $name = array_key_exists('name', $_POST) ? $_POST['name'] : '';
        $name = filter_var($name, FILTER_SANITIZE_STRING);
        $this->name = $name ?: '';

        $email = array_key_exists('email', $_POST) ? $_POST['email'] : '';
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        // Probably we can lose some strange but valid exists emails
        // Highly recommend to use services for check existing of email
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        $this->email = $email ?: '';

        $password = array_key_exists('password', $_POST) ? $_POST['password'] : '';
        $this->password = $password;

        $passwordRepeat = array_key_exists('password_repeat', $_POST) ? $_POST['password_repeat'] : '';
        $this->passwordRepeat = $passwordRepeat;

        $confirmPD = array_key_exists('confirm_pd', $_POST) ? $_POST['confirm_pd'] : '';
        $confirmPD = filter_var($confirmPD, FILTER_SANITIZE_STRING);
        $this->confirmPD = $confirmPD ?: '';
    }

    /**
     * @return array Array contains form data fields needs for creating user.
     *      Must be called after collect
     */
    public function getFormData(): array
    {
        return [
            'email' => $this->email,
            'name' => $this->name,
            'password' => $this->password
        ];
    }

    /**
     * Uses all class defined check for validate filtered values
     *
     * @return bool is true when all check passed else false
     */
    public function isValidSignup(): bool
    {
        return $this->confirmPD === 'on'
               && $this->checkName()
               && $this->checkEmail()
               && $this->checkPassword();
    }

    public function render()
    {
        include_once __DIR__ . '/../../public/templates/signup.php';
    }
}