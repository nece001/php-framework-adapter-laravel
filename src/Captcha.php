<?php

namespace Nece\Framework\Adapter;

use Mews\Captcha\Captcha as MewsCaptcha;
use Nece\Framework\Adapter\Contract\Captcha as ContractCaptcha;

class Captcha implements ContractCaptcha
{
    /**
     * 生成验证码图片内容
     *
     * @return string
     */
    public function image(): string
    {
        $captcha = app(MewsCaptcha::class);
        return $captcha->create();
    }

    /**
     * 校验验证码值
     *
     * @param string $phrase
     * @return boolean
     */
    public function check(string $phrase): bool
    {
        $captcha = app(MewsCaptcha::class);
        return $captcha->check($phrase);
    }
}