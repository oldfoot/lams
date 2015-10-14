<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."modules/leave/classes/user_balances.php");

class YearPlannerXML{

	function __construct() {
		$this->errors="";


		$this->head2="";
		$f = "<categories  bgColor='4567aa' fontColor='ff0000'>
										<category start='1/1/2008' end='31/12/2008' align='center' name='Months'  alpha='' font='Verdana' fontColor='ffffff' isBold='1' fontSize='16' />
									</categories>";


		/*
		$this->tasks="<tasks  width='10' >
										<task name='Planned' processId='1' start='1/1/2008' end='31/1/2008' id='1-1' color='4567aa' height='10' topPadding='5' animation='0'/>
										<task name='Planned' processId='1' start='1/8/2008' end='31/8/2008' id='1-1' color='4567aa' height='10' topPadding='5' animation='0'/>
										<task name='Actual' processId='1' start='7/3/2008' end='22/4/2008' id='1' color='cccccc' alpha='100'  topPadding='19' height='10' />

									</tasks>";
									*/
		$this->connectors="<connectors>
												<connector fromTaskId='3' toTaskId='5' color='4567aa' thickness='2' fromTaskConnectStart='1'/>
												<connector fromTaskId='8' toTaskId='9' color='4567aa' thickness='2' fromTaskConnectStart='1'/>
											</connectors>";
		$this->closing="</chart>";
	}
	public function Draw($dir) {

		$this->GetUsersLeave();
		$this->GetMonthsPeriod();
		$this->users=$this->GetUsers();

		$result=file_put_contents($dir,$this->chart_start.$this->head2.$this->months_xml.$this->user_xml.$this->balances.$this->tasks.$this->closing);
		if ($result) {
			return true;
		}
		else {
			$this->Errors("unable to create file");
		}
	}

	public function GetUsers() {

		$this->db=$GLOBALS['db'];


		$obj_user = new UserBalances;
		$current_period = $GLOBALS['obj_us']->GetInfo("period_id");

		$sql="SELECT um.user_id,um.full_name
					FROM ".$GLOBALS['database_prefix']."core_space_users csu, ".$GLOBALS['database_prefix']."core_user_master um
					WHERE csu.workspace_id = ".$GLOBALS['workspace_id']."
					AND csu.user_id = um.user_id
					";
		//echo $sql."<br>";
		$result = $this->db->Query($sql);
		$this->user_xml="<processes headerText='Tasks' fontColor='ffffff' fontSize='10' isBold='1' isAnimated='1' bgColor='4567aa'  headerVAlign='right' headerbgColor='4567aa' headerFontColor='ffffff' headerFontSize='16' width='80' align='left'>\n";
		$this->balances="<dataTable showProcessName='1' nameAlign='left' fontColor='000000' fontSize='10' isBold='1' headerBgColor='00ffff' headerFontColor='4567aa' headerFontSize='11' vAlign='right' align='left'>
										<dataColumn align='center' headerfontcolor='ffffff'  headerbgColor='4567aa'  bgColor='eeeeee' headerText='Days.' width='70' isBold='0'>\n";

		if ($this->db->NumRows($result) > 0) {
			while($row = $this->db->FetchArray($result)) {


				// get the list of users
				$this->user_xml.="<process Name='".$row['full_name']."' id='".$row['user_id']."' />\n";

				// get the list of leave taken for the second column
				//echo $row['user_id']."<br />";
				$obj_user->SetParameters($row['user_id']);
				$total=$obj_user->LeaveTakenPeriod($current_period);
				$this->balances.="<text label='$total' />\n";

			}
		}
		$this->user_xml.="</processes>\n";
		$this->balances.="</dataColumn>
									</dataTable>\n";

	}

	public function GetUsersLeave() {

		$this->db=$GLOBALS['db'];
		$current_period = $GLOBALS['obj_us']->GetInfo("period_id");

		$sql="SELECT la.user_id, la.date_from, la.date_to
					FROM ".$GLOBALS['database_prefix']."leave_applications la
					WHERE la.workspace_id = ".$GLOBALS['workspace_id']."
					AND period_id = $current_period
					";
		//echo $sql."<br>";
		$result = $this->db->Query($sql);

		$this->tasks = "<tasks  width='10' >\n";
		if ($this->db->NumRows($result) > 0) {
			while($row = $this->db->FetchArray($result)) {
				$pieces_from = explode("-",$row['date_from']);
				$pieces_to = explode("-",$row['date_to']);
				$date_from_form = $pieces_from[2]."/".$pieces_from[1]."/".$pieces_from[0];
				$date_to_form = $pieces_to[2]."/".$pieces_to[1]."/".$pieces_to[0];
				$this->tasks .= "<task name='Planned' processId='1' start='".$date_from_form."' end='".$date_to_form."' id='".$row['user_id']."-".$row['user_id']."' color='4567aa' height='10' topPadding='5' animation='0'/>\n";
				//$this->tasks .= "<task name='Actual' processId='1' start='7/3/2008' end='22/4/2008' id='".$row['user_id']."' color='cccccc' alpha='100'  topPadding='19' height='10' />\n";
			}
		}

		$this->tasks .= "</tasks>\n";

	}

	public function GetMonthsPeriod() {
		$f = $GLOBALS['obj_us']->GetInfo("date_from");
		$t = $GLOBALS['obj_us']->GetInfo("date_to");
		//echo $GLOBALS['obj_us']->GetInfo("date_from");
		//echo "<br />";
		//echo $GLOBALS['obj_us']->GetInfo("date_to");

		$pieces_from = explode("-",$f);
		$pieces_to = explode("-",$t);

		$months_from = 12 - $pieces_from[1] + 1;
		//echo "Months from: $months_from <br />";

		if ($pieces_from[0] != $pieces_to[0]) {
			if (($pieces_to[0] - $pieces_from[0]) > 1) {
				$months_from+=($pieces_to[0] - $pieces_from[0])*12;
			}
			else {
				$months_from += 12 - $pieces_to[1] +1;
			}
		}
		//echo "Months from: $months_from <br />";
		$start_month = strtotime($f);
		$curr_month = $start_month;
		$date_from_form = $pieces_from[2]."/".$pieces_from[1]."/".$pieces_from[0];
		$date_to_form = $pieces_to[2]."/".$pieces_to[1]."/".$pieces_to[0];
		$this->chart_start="<chart dateFormat='dd/mm/yyyy' hoverCapBorderColor='2222ff' hoverCapBgColor='e1f5ff' ganttWidthPercent='80' ganttLineAlpha='80' canvasBorderColor='024455' canvasBorderThickness='0' gridBorderColor='4567aa' gridBorderAlpha='20'>
									<categories  bgColor='009999'>
										<category start='".$date_from_form."' end='".$date_to_form."' align='center' name='Annual Leave Planner'  fontColor='ffffff' isBold='1' fontSize='16' />
									</categories>";

		$this->months_xml = "<categories  bgColor='ffffff' fontColor='1288dd' fontSize='10' >\n";

		for ($i=0;$i<$months_from;$i++) {
			$curr_month = mktime(0, 0, 0, date("m",$start_month)+$i, date("d",$curr_month), date("Y",$curr_month));
			//echo date("M",$curr_month);
			$temp_next_month = mktime(0, 0, 0, date("m",$curr_month)+1, date("d",$curr_month), date("Y",$curr_month));
			$last_day_curr_month = mktime(0, 0, 0, date("m",$temp_next_month), date("d",$temp_next_month)-1, date("Y",$temp_next_month));
			$this->months_xml .= "<category start='".date("d/m/Y",$curr_month)."' end='".date("d/m/Y",$last_day_curr_month)."' align='center' name='".date("M",$curr_month)."'  isBold='1' />\n";
		}

		$this->months_xml .= "</categories>\n";
		echo $this->months_xml;
	}


	public function Debug($desc) {
		if ($this->debug==True) {
			echo $desc."<br>\n";
		}
	}

	private function Errors($err) {
		$this->errors.=$err."<br>";
	}

	public function ShowErrors() {
		return $this->errors;
	}
}
?>