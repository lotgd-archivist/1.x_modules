<?php

function wettkampf_statueblumen_run_private($op){
	global $session;
	page_header("Der Platz der Völker");
		$wieoft=get_module_pref("blumenniederlegen");
		if ($wieoft==5){
	 		output ("`@Du willst gerade zum Blumenhändler gehen, als Dir auffällt, dass Du schon sehr viele Blumen niedergelegt hast. Das führt nur zu Sozialneid - also lässt Du es bleiben. Aus Erfahrung weißt Du, dass die Blumen am Ende eines Festes eingesammelt werden.");
		}else if ($session[user][gold]<50){
			output ("`@Du zählst Dein Geld und musst leider feststellen, dass Du Dir keine Blumen leisten kannst."); 
		}else{
			output ("`@Du gehst zu einem Blumenhändler, der einen dauerhaften Stand im unteren Marktbereich hat und kaufst einen schönen Strauß Blumen, um ihn auf dem Rasenplatz vor der Statue niederzulegen.`n`n");
			output ("`@Dabei wirst Du von einigen Bürgern beobachtet.");
			$session[user][gold]-=50;
			$blumen=get_module_setting("statueblumen");
			$blumenneu=$blumen+1;
			set_module_setting("statueblumen", $blumenneu);
			$wieoftneu=$wieoft+1;
			set_module_pref("blumenniederlegen", $wieoftneu);
			
			$ereignis=e_rand(-1,2);
			if ($ereignis >= 1){
				if ($wieoftneu==2){
					output ("`@Sie nicken anerkennend, weil Du schon einmal Blumen hergebracht hast. Irgendwie fühlst Du Dich dadurch ... `igut`i.");
					if (is_module_active('alignment')) align("2");
				}else if ($wieoftneu>3 && $wieoftneu<5){
					output ("`@Sie nicken anerkennend, weil Du schon öfter Blumen hergebracht hast. Irgendwie fühlst Du Dich dadurch ... `igut`i.");
					if (is_module_active('alignment')) align("1");
				}else if ($wieoftneu==5){
					output ("`@Einige von ihnen schütteln empört den Kopf, weil Du anscheinend zuviel Geld hast, dass Du soviele Blumen hierherbringst. `#'Ein klarer Fall von Sozialneid'`@, denkst Du Dir - und gehst mit einer abfälligen Geste davon ...");
					if (is_module_active('alignment')) align("-2");
				}
			}
		}
	
		addnav("Zurück","runmodule.php?module=wettkampf&op1=statue");
	page_footer();
}
?>