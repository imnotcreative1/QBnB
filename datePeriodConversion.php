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
        $InitDate = "2016-01-01";
        $start = strtotime($InitDate);
        $end = strtotime($date);

        $days_between = floor(abs($start - $end) / 86400);
        return ceil($days_between/7);
        //echo "\n" . $days_between;
    }

    function printDate($date){
        $endDate = Date('y:m:d', $date + strtotime("+ 7 days"));
        $Month = array("JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JULY", "AUG", "SEPT", "OCT", "NOV", "DEC");
        $monthAsNum= (int) substr($date, 3, 2);
        //echo $date . " </br>"; 
        //echo $Month[$monthAsNum] . " </br>"; 
        return $Month[$monthAsNum  - 1] . " " . substr($date, 6, 2)  . ", 20"  . substr($date, 0, 2) ;
    }
    

?>
