<?php

namespace Sxqibo\FastSpapi;


/*******************************/
/** Report/Feed content types **/
/*******************************/

class ContentType
{
    public const CSV   = 'text/csv';
    public const JSON  = 'application/json';
    public const PDF   = 'application/pdf';
    public const PLAIN = 'text/plain';
    public const TAB   = 'text/tab-separated-values;charset=UTF-8';
    public const XLSX  = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
    public const XML   = 'text/xml;charset=UTF-8';
}
