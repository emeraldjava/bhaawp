<?php
echo '<div class="wrap"><h2>Edit Race Result : '.$_GET['raceresult'].' '.$raceLink.'</h2></div>';
echo '<form action="'.$link.'" method="POST">
    <fieldset>
        <label>Result:<input type="text" readonly id="bhaa_raceresult_id" name="bhaa_raceresult_id" value="'.$raceResult->id.'"/></label><br>
        <label>Runner:<input type="text" id="bhaa_runner" name="bhaa_runner" value="'.$raceResult->runner.'"/></label><br>
        <label>Race:<input type="text" readonly id="bhaa_race" name="bhaa_race" value="'.$raceResult->race.'"/></label><br>
        <label>Time:<input type="text" id="bhaa_time" name="bhaa_time" value="'.$raceResult->racetime.'"/></label><br>
        <label>Number:<input type="text" id="bhaa_number" name="bhaa_number" value="'.$raceResult->racenumber.'"/></label><br>
        <label>Pre Std:<input type="text" id="bhaa_pre_standard" name="bhaa_pre_standard" value="'.$raceResult->standard.'"/></label><br>
        <label>Post Std:<input type="text" id="bhaa_post_standard" name="bhaa_post_standard" value="'.$raceResult->poststandard.'"/></label><br>
        <input type="hidden" name="action" value="bhaa_race_result_save"/>
        <input type="submit" name="submit" value="Update Race Result"/>
    </fieldset>
</form>';

echo '<form action="'.$link.'" method="POST">
    <fieldset>
        <input type="hidden" id="bhaa_raceresult_id" name="bhaa_raceresult_id" value="'.$raceResult->id.'"/>
        <input type="hidden" id="bhaa_race" name="bhaa_race" value="'.$raceResult->race.'"/>
        <input type="hidden" name="action" value="bhaa_race_result_delete"/>
        <input type="submit" name="submit" value="Delete Race Result"/>
    </fieldset>
</form>';
?>