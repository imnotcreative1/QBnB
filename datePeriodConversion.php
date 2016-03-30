<?php
    //Convert Period to Date
    //$period = 5;
    function periodToDate($period){
        $addedTime = "+ " . $period . " weeks";
        $InitialDate=date('16:01:01');

        // add period weeks to  start date (January 1st, 2016)
        $NewDate=Date('y:m:d', $InitialDate + strtotime($addedTime));
        //echo $NewDate;
        return $NewDate;
    }
    //periodToDate($period);

    //Convert Date to Period
    //$date = "2016-01-01";
    function dateToPeriod($date){
        echo "here\n";
        $InitDate = "2016-01-01";
        $start = strtotime($InitDate);
        $end = strtotime($date);

        $days_between = floor(abs($start - $end) / 86400);
        return $days_between;
        //echo "\n" . $days_between;
    }
    

?>
