<?php
if ($session['user']['race']==$race){
        if ($session[bufflist][Blutdurst][rounds]==0) {
		apply_buff("racialbenefit",array(
				"name"=>"`4`bBefriedigter Jagdtrieb`b`0",
				"atkmod"=>"(<attack>?(-1*(-1+((1+floor(<level>/10))/<attack>))):0)",
				"defmod"=>"(<defense>?(-1*(-1+((1+floor(<level>/10))/<defense>))):0)",
				"allowinpvp"=>1,
                "allowintrain"=>1,
                "rounds"=>-1,
                "schema"=>"module-racevampir",
				"activate"=>"offense",
				)
            );
        }
     }
?>
