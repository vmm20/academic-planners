
<!DOCTYPE html>
<html>
    <head>
        <?php $school = "LASA" ;
        $schoolYear = "2019-2020" ?>
        <title>Planner <?= $schoolYear ?>: <?= $school ?></title>
        <link rel="stylesheet" type="text/css" href="css/planners.css">
        <?php $lineLabels = array(
            "English", "",
            "Math", "",
            "History", "",
            "Science", "",
            "Other", ""
            ) ?>
        <!-- Keys in $dayCaptions should always be in the format Y-m-d -->
        <?php if($_GET['start'] == NULL) {
            $year_start = new DateTime('now');
        } else {
            $year_start = new DateTime($_GET['start']);
        }

        $start_dow = (int)$year_start->format("w");


        // Force the week to start on Monday.
        while($start_dow < 1) {
            $year_start->add(new DateInterval('P1D'));
            $start_dow = (int)$year_start->format("w");
        }
        while($start_dow > 1) {
            $year_start->sub(new DateInterval('P1D'));
            $start_dow = (int)$year_start->format("w");
        }


        if($_GET['end'] == NULL) {
            $year_end = clone $year_start;
            $year_end->add(new DateInterval('P1D'));
        } else {
            $year_end = new DateTime($_GET['end']);
        }

        $dayCaptions = [];

        if($_GET['calendar'] != NULL && file_exists("calendars/{$_GET['calendar']}.csv")) {
            $calendarFile = fopen("calendars/{$_GET['calendar']}.csv", "r");
            $calendarEvents = [];
            while(($calendarEvent = fgetcsv($calendarFile, ",")) !== FALSE) {
                $dayCaptions[$calendarEvent[0]] = $calendarEvent[1];
            }
            
            fclose($calendarFile);
        }
        
        echo "<pre>";
        print_r($dayCaptions, FALSE);
        echo "</pre>";
        ?>
        

    </head>
    <body>
        <?php
        for ($p=0; $p < $_GET['blankPagesBefore']; $p++) { ?>
            <div class="blank page"></div>
        <?php }
        $intervalWeeks = new DateInterval('P7D');
        $year_period = new DatePeriod($year_start, $intervalWeeks, $year_end);
        $week_index_offset = 0;
        foreach ($year_period as $week_index => $week_start) {
            $intervalDays = DateInterval::createFromDateString('1 day');
            $week_period_all_days = new DatePeriod($week_start, $intervalDays, 6);
            $week_period_all_weekdays = new DatePeriod($week_start, $intervalDays, 4);
            $week_period_left = new DatePeriod($week_start, $intervalDays, 2);
            $monday = clone $week_start;
            $week_start->add(new DateInterval('P3D'));
            $week_period_right = new DatePeriod($week_start, $intervalDays, 1);
            $week_start->add(new DateInterval('P1D'));
            $friday = clone $week_start;
            $week_start->add(new DateInterval('P1D'));
            $week_period_weekends = new DatePeriod($week_start, $intervalDays, 1);
            $week_periods = array(
                "left" => $week_period_left,
                "right" => $week_period_right
            );
            $week_number = $week_index + 1 - $week_index_offset;

            foreach ($week_periods as $page_side => $week_period) { ?>
                <div class="page <?= $page_side ?>">
                    <!-- if on left side of page, include week number and month-year header -->
                    <?php if($page_side == "left") { ?>
                        <div class="weekNumber">&nbsp;</div>
                        <div class="monthHeader"><?= $monday->format('F Y') ?></div>
                    <?php } ?>
                    
                    <?php foreach ($week_period as $day) { ?>
                        <div class="day <?= $day->format('l') ?>">
                            <div class="dayHeaders">
                                <div class="date"><?= $day->format('j') ?></div>
                                <div class="dayOfWeek"><?= $day->format('l') ?></div>
                                <div class="completed">✓</div>
                                <div class="due">Due</div>
                                <div class="dayCaption"><?= $dayCaptions[$day->format('Y-m-d')] ?></div>
                            </div>
                            <div class="lines">
                                <?php foreach ($lineLabels as $lineLabel) {
                                    $lineTier = $lineLabel !== "" ? "primary" : "secondary" ?>
                                    <div class="completed cell <?= $lineTier ?>"></div>
                                    <div class="due cell <?= $lineTier ?>"></div>
                                    <div class="line <?= $lineTier ?>"><?= $lineLabel?></div>
                                <?php } ?>
                            </div>
                            
                        </div>
                    <?php } ?>    <!-- end of the half-week -->
                    
                    <!-- if on right side of page, add weekend block -->
                    <?php if($page_side == "right") { ?>
                        <div class="weekend weekendSection">
                            <?php foreach ($week_period_weekends as $day) { ?>
                                <div class="day weekend <?= $day->format('l') ?>">
                                    <div class="dayHeaders weekend">
                                        <div class="date weekend"><?= $day->format('j') ?></div>
                                        <div class="dayOfWeek weekend"><?= $day->format('l') ?></div>
                                        <div class="dayCaption weekend"><?= $dayCaptions[$day->format('Y-m-d')] ?></div>
                                    </div>
                                    
                                    <div class="lines weekend">
                                        <?php for ($i=0; $i < 5; $i++) { ?>
                                            <div class="line weekend"></div>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>

                        </div>

                    <?php } ?>
                    
                    <!-- if on right side of page, add notes block -->
                    <?php if($page_side == "right") { ?>
                        <div class="notes notesSection">
                            <div class="notesHeaders">Notes</div>
                            <?php foreach ($week_period_all_weekdays as $day) { ?>
                                <div class="line notes primary"><div class="notesDate"><?= $day->format('j') ?></div><?= $day->format('l') ?></div>
                                <div class="line notes secondary"></div>
                            <?php } ?>
                            <div class="line notes primary"></div>
                            <div class="line notes secondary"></div>
                        </div>
                            
                    <?php } ?>
                    
                    <!-- add the page footer -->
                    <div class="footer <?= $page_side ?>"><?= $page_side == "left" ? $monday->format('j F') : $friday->format('j F') ?></div>

                        
                </div>      <!-- end of the page -->

            <?php } ?>     <!-- end of the week -->

            
        
        <?php } ?>     <!-- end of the year -->


    </body>
</html>