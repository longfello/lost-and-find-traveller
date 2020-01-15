<?php
/**
 * Created by PhpStorm.
 * User: Miloslawsky
 * Date: 12.02.2015
 * Time: 13:52
 */

class EnumRouteDeviation extends Enum{
  const DEVIATION_NONE           = 'none';
  const DEVIATION_WITHIN_15_MIN  = 'within15min';
  const DEVIATION_WITHIN_30_MIN  = 'within30min';
  const DEVIATION_WITHIN_1_HOUR  = 'within1hour';
  const DEVIATION_WITHIN_2_HOURS = 'within2hours';
}