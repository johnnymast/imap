<?php
/**
 * Created by PhpStorm.
 * User: jmast
 * Date: 6-3-2019
 * Time: 20:11
 */

namespace Redbox\Imap\Resources;

class AuthResource extends ResourceAbstract
{
    public function authenticate()
    {

        $response = $this->call('LOGIN');
    }
}