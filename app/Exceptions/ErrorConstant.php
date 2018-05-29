<?php

namespace App\Exceptions;


class ErrorConstant
{
    const SYSTEM_ERR = 500000;
    const SYSTEM_ERR_PDO = 500001;

    const PARAMS_LOST = 500417;
    const PARAMS_ERROR = 500418;
    const DATA_ERR = 500404;

    const SMS_SEND_ATTEND_FAIL = 200100;
    const SMS_CHECK_ERR = 200500;

    const UN_AUTH_ERROR = 500401;
}