<?php

  class Sphinx {

    public static function SearchCity($search_for){
      $table = 'iw_autocomplete';
      $s = new SphinxClient;
      $s->setServer("localhost", 9312);
      $s->setMatchMode(SPH_MATCH_EXTENDED2);
      $s->SetSortMode(SPH_SORT_RELEVANCE);
      $s->setMaxQueryTime(300);
      $s->SetLimits(0, 50, 50);

      // SPH_RANK_SPH04, но точное совпадение вверх
      $s->setRankingMode(SPH_SORT_EXPR, 'sum((4*lcs+2*(min_hit_pos==1)+exact_hit*100)*user_weight)*1000+bm25');
      $query = $search_for.'*';

      $result = $s->Query($query, $table);

      $result['items'] = array();

      if (isset($result['matches'])) {
        foreach($result['matches'] as $internal_id => $info) {
          $id = $info['attrs']['geonameid'];

          $result['items'][$id] = $id;
        }

        $ids = implode(',', $result['items']);
        $query = "SELECT slug, city.name_ru city_ru, city.name_en city_en,
                         geocountry.name_ru country_ru, geocountry.name_en country_en,
                         geozone.name_ru zone_ru,geozone.name_en zone_en,
                         city.id geonameid, city.latitude, city.longitude,
 												 Y(EndPoint(area)) end_lat, X(EndPoint(area)) end_lng,
                         Y(StartPoint(area)) start_lat, X(StartPoint(area)) start_lng

          FROM geoname city
          LEFT JOIN geozone ON geozone.id = city.zone
          LEFT JOIN geocountry ON geocountry.id = city.country
          WHERE city.id IN ($ids) ORDER BY city.population DESC";

        $result['items'] = Yii::app()->db->createCommand($query)->queryAll();
      }

      $result['total'] = isset($result['total'])?$result['total']:0;
      unset($result['matches']);

      return $result['items'];
    }

    private static function GetSphinxKeyword($sQuery)
    {
      $aKeyword = array();
      $aRequestString=preg_split('/[\s,-]+/', $sQuery, 5);
      if ($aRequestString) {
        foreach ($aRequestString as $sValue)
        {
          if (strlen($sValue)>2)
          {
            $aKeyword[] .= "(".$sValue." | *".$sValue."*)";
          }
        }
      }
      $sSphinxKeyword = '(('.implode(" & ", $aKeyword).') | ("'.$sQuery.'"))';
      return $sSphinxKeyword;
    }
  }