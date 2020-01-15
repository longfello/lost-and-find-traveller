<?php
/**
 * Created by PhpStorm.
 * User: Miloslawsky
 * Date: 12.02.2015
 * Time: 13:52
 */

class EnumRoutePunctuality extends Enum{
  const PUNCTUALITY_EXACTLY        = 'exactly';
  const PUNCTUALITY_WITHIN_15_MIN  = 'within15min';
  const PUNCTUALITY_WITHIN_30_MIN  = 'within30min';
  const PUNCTUALITY_WITHIN_1_HOUR  = 'within1hour';
  const PUNCTUALITY_WITHIN_2_HOURS = 'within2hours';
}