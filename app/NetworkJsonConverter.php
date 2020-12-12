<?php

class NetworkJsonConverter implements BaseConverter
{
    function getJsonData($hospital,$house)
    {
        $result  = array();
        $point_start_query = <<<EOI
            select p.*, node.idn, ho.name, ho.address, ho.description
            from node
            left join hospital ho on node.idn = ho.idn
            left join point p on p.idp = node.idp
            where ho.description like '%$hospital%'
EOI;

        $point_start = Connection::query($point_start_query);
        foreach($point_start as $point){
            $result[] = GraphicUtil::generate2DPointFrom($point);
        }
         $idnstart = $point_start[0]['idn']; 
      // query get data point end
      $text = 'shop';
        $point_finish_query = <<<EOI
            select  p.*, node.idn,ho.name, ho.address, ho.description
            from node
            left join house ho on node.idn = ho.idn
            left join point p on p.idp = node.idp
            where ho.description like '%$house%'
EOI;
        
        $point_finish = Connection::query($point_finish_query);
        foreach($point_finish as $point){
            $result[] = GraphicUtil::generate2DPointTo($point);
        }
        $idnfinish =  $point_finish[0]['idn'];

    // get id arcs of 2 point begin and end
    
        $idarcs_query = <<<EOI
        select a.ida
        from arc a
        where a.inb = $idnstart and a.ine = $idnfinish
EOI;
        $idarcs = Connection::query($idarcs_query);
        //get ida first
        $idafirst = $idarcs[0]['ida'];

        //get amount time by arc first
        $timeArcFisrt_query = <<<EOI
        select amount_time, ida
        from arc
        where ida = $idafirst
EOI;
        $timeArcFisrt = Connection::query($timeArcFisrt_query);
        $time = $timeArcFisrt[0]['amount_time'];
        $ida_best = $timeArcFisrt[0]['ida'];
        $idEvent = null;
        foreach ($idarcs as $ida) {
            $timeTemp = null;
            $timestamp = time();
           date_default_timezone_set('Asia/Ho_Chi_Minh');
            $hour = date('H:i:s', $timestamp);
            // query to get  amount time dealay route have event
            $idanew = $ida['ida'] ;
           $DoSumAmountTime_query = <<<EOI
            select  e.amount_time_delay, ea.ide, arc.amount_time
            from event_arc ea
            left join event e on ea.ide = e.ide
            left join arc on ea.ida = arc.ida
            where arc.ida = $idanew and e.time_start <= '$hour' and e.time_finish >= '$hour'
EOI;
         $DoSumAmountTime =  Connection::query($DoSumAmountTime_query);
            if(!empty($DoSumAmountTime)){
                $timeTemp = $DoSumAmountTime[0]['amount_time_delay'] + $DoSumAmountTime[0]['amount_time'];
             }
            else{
                //Viết câu lấy amount của arc sau đó gắn vào TimeTemp
                $amount_time_query = <<<EOI
                select amount_time
                from arc
                where ida = $idanew
EOI;
                $amount_time =  Connection::query($amount_time_query);
                $timeTemp = $amount_time[0]['amount_time'];
            }
            if($ida_best == $ida['ida']){
                $time = $timeTemp;
            }
            else{
                if($time > $timeTemp){
                $time = $timeTemp;
               $ida_best = $idanew;
                }
            }
        }
    // get data route optimation
      $route_query = <<<EOI
        select  p.*, ap.*
        from arc_point ap
        left join point p on p.idp = ap.idp
        where ap.ida = $ida_best
        order by ap.ida, ap.seq
EOI;
        $routes = Connection::query($route_query);
        $current_line_id = null;
        $current_line = null;
        foreach ($routes as $route) {
            if ($current_line_id != $route['ida']){
                if($current_line != null) {
                    $result[] = $current_line;
                }
                $current_line = GraphicUtil::generate2DLine($route);
                $current_line_id = $route['ida'];
            } else {
                $current_line = GraphicUtil::generate2DLine($route, $current_line);
            }
        }
        if($current_line != null) {
            $result[] = $current_line;
        }
    // get event
    $timestamp = time();
    date_default_timezone_set('Asia/Ho_Chi_Minh');
     $hour = date('H:i:s', $timestamp);
    $event_query = <<<EOI
        select  p.*, ep.*, e.*
        from event_point ep
        left join point p on p.idp = ep.idp
        left join event e on e.ide =  ep.ide
        where e.time_start <= '$hour' and e.time_finish >= '$hour'
        order by ep.ide
EOI;
        $events = Connection::query($event_query);
          $current_event_id = null;
           $current_event= null;
          foreach ($events as $event) {
            if ($current_event_id != $event['ide']){
                if($current_event != null) {
                    $result[] = $current_event;
                }
                $current_event = GraphicUtil::generate2DLineEvent($event);
                $current_event_id = $event['ide'];
            } else {
                $current_event = GraphicUtil::generate2DLineEvent($event, $current_event);
            }
        }
        if($current_event != null) {
            $result[] = $current_event;
        }

        return $result;
    }
}
