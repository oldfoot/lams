<?php
include_once($GLOBALS['dr']."classes/reporting/fusion_column2d.php");

class MonthlyLeaveTransactions {
	public function __construct() {
		$this->ReportTitle = "Monthly Leave Transactions";
		$this->ReportDescription = "Display monthly leave transactions";
		$this->ReportImage = "bar";
	}
	public function GetInfo($var) {
		if (ISSET($this->$var)) {
			return $this->$var;
		}
		else {
			return false;
		}
	}
	public function DrawReport() {

		global $head;
		$head->IncludeFile("fusioncharts");

		$sql="SELECT count(*) AS total, monthname(date_from) as legend
					FROM leave_applications la
					WHERE la.workspace_id = ".$GLOBALS['workspace_id']."
					AND la.period_id = '".$GLOBALS['obj_us']->GetInfo("period_id")."'
					GROUP BY month(date_from)
					ORDER BY month(date_from)
					";
		//echo $sql;
		$obj = new FusionColumn2d;
		$obj->GenHead();
		$obj->GenLegendDB($sql);
		$obj->GenFooter();
		$obj->SaveToDir();

		$c = "";

		$c .= "<h3>Leave by category for the current period</h3>
					<div id='chartdiv' align='center'>FusionCharts.</div>
      		<script type=\"text/javascript\">
			   		var chart = new FusionCharts(\"".$GLOBALS['wb']."include/FusionChartsFree/Charts/FCF_Column3D.swf\",\"ChartId\",\"500\",\"369\");
			   		chart.setDataURL(\"".$GLOBALS['wb']."bin/reporting/xml/column2d_".$_SESSION['sid'].".xml\");
			   		chart.render(\"chartdiv\");
					</script> ";

		return $c;

		$obj_gxf=new GenXMLFile();

		/* GRAB THE DATA HERE */
		$obj_gd=new GenData;
		$sql="SELECT count(*) AS total, monthname(date_from) as month_name
					FROM leave_applications la
					WHERE la.workspace_id = ".$GLOBALS['workspace_id']."
					AND year(date_from) = year(curdate())
					GROUP BY month(date_from)
					ORDER BY month(date_from)
					";
		$array_month_totals=$obj_gd->GenMonthLongYearData($sql);

		/* CONTINUE WITH THE GEN XML STUFF */
		$obj_gxf->SetVar("header","Monthly leave transactions for ".date("Y"));
		/* SET THE ARRAY OF DATA FROM ABOVE */
		$obj_gxf->SetVar("arr_data_values",$array_month_totals);
		$obj_gxf->GenHead();
		$obj_gxf->GenLegend();
		$obj_gxf->GenFooter();
		$obj_gxf->SaveToDir();


		$c="<script language=\"javascript\" type=\"text/javascript\">if(typeof deconcept==\"undefined\")var deconcept=new Object();\n";
		$c.="if(typeof deconcept.util==\"undefined\")deconcept.util=new Object();\n";
		$c.="if(typeof deconcept.SWFObjectUtil==\"undefined\")deconcept.SWFObjectUtil=new Object();deconcept.SWFObject=function(swf,id,w,h,ver,c,useExpressInstall,quality,xiRedirectUrl,redirectUrl,detectKey){if(!document.getElementById){return}this.DETECT_KEY=detectKey?detectKey:'detectflash';\n";
		$c.="this.skipDetect=deconcept.util.getRequestParameter(this.DETECT_KEY);\n";
		$c.="this.params=new Object();\n";
		$c.="this.variables=new Object();\n";
		$c.="this.attributes=new Array();\n";
		$c.="if(swf){this.setAttribute('swf',swf)}if(id){this.setAttribute('id',id)}if(w){this.setAttribute('width',w)}if(h){this.setAttribute('height',h)}if(ver){this.setAttribute('version',new deconcept.PlayerVersion(ver.toString().split(\".\")))}this.installedVer=deconcept.SWFObjectUtil.getPlayerVersion();\n";
		$c.="if(c){this.addParam('bgcolor',c)}var q=quality?quality:'high';\n";
		$c.="this.addParam('quality',q);\n";
		$c.="this.setAttribute('useExpressInstall',useExpressInstall);\n";
		$c.="this.setAttribute('doExpressInstall',false);\n";
		$c.="var xir=(xiRedirectUrl)?xiRedirectUrl:window.location;\n";
		$c.="this.setAttribute('xiRedirectUrl',xir);\n";
		$c.="this.setAttribute('redirectUrl','');\n";
		$c.="if(redirectUrl){this.setAttribute('redirectUrl',redirectUrl)}};\n";
		$c.="deconcept.SWFObject.prototype={setAttribute:function(name,value){this.attributes[name]=value},getAttribute:function(name){return this.attributes[name]},addParam:function(name,value){this.params[name]=value},getParams:function(){return this.params},addVariable:function(name,value){this.variables[name]=value},getVariable:function(name){return this.variables[name]},getVariables:function(){return this.variables},getVariablePairs:function(){var variablePairs=new Array();\n";
		$c.="var key;\n";
		$c.="var variables=this.getVariables();\n";
		$c.="for(key in variables){variablePairs.push(key+\"=\"+variables[key])}return variablePairs},getSWFHTML:function(){var swfNode=\"\";\n";
		$c.="if(navigator.plugins&&navigator.mimeTypes&&navigator.mimeTypes.length){if(this.getAttribute(\"doExpressInstall\")){this.addVariable(\"MMplayerType\",\"PlugIn\")}swfNode='<embed type=\"application/x-shockwave-flash\" src=\"'+this.getAttribute('swf')+'\" width=\"'+this.getAttribute('width')+'\" height=\"'+this.getAttribute('height')+'\"';\n";
		$c.="swfNode+=' id=\"'+this.getAttribute('id')+'\" name=\"'+this.getAttribute('id')+'\" ';\n";
		$c.="var params=this.getParams();\n";
		$c.="for(var key in params){swfNode+=[key]+'=\"'+params[key]+'\" '}var pairs=this.getVariablePairs().join(\"&\");\n";
		$c.="if(pairs.length>0){swfNode+='flashvars=\"'+pairs+'\"'}swfNode+='/>'}else{if(this.getAttribute(\"doExpressInstall\")){this.addVariable(\"MMplayerType\",\"ActiveX\")}swfNode='<object id=\"'+this.getAttribute('id')+'\" classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" width=\"'+this.getAttribute('width')+'\" height=\"'+this.getAttribute('height')+'\">';\n";
		$c.="swfNode+='<param name=\"movie\" value=\"'+this.getAttribute('swf')+'\"/>';\n";
		$c.="var params=this.getParams();\n";
		$c.="for(var key in params){swfNode+='<param name=\"'+key+'\" value=\"'+params[key]+'\"/>'}var pairs=this.getVariablePairs().join(\"&\");\n";
		$c.="if(pairs.length>0){swfNode+='<param name=\"flashvars\" value=\"'+pairs+'\"/>'}swfNode+=\"</object>\"}return swfNode},write:function(elementId){if(this.getAttribute('useExpressInstall')){var expressInstallReqVer=new deconcept.PlayerVersion([6,0,65]);\n";
		$c.="if(this.installedVer.versionIsValid(expressInstallReqVer)&&!this.installedVer.versionIsValid(this.getAttribute('version'))){this.setAttribute('doExpressInstall',true);\n";
		$c.="this.addVariable(\"MMredirectURL\",escape(this.getAttribute('xiRedirectUrl')));\n";
		$c.="document.title=document.title.slice(0,47)+\"-Flash Player Installation\";\n";
		$c.="this.addVariable(\"MMdoctitle\",document.title)}}if(this.skipDetect||this.getAttribute('doExpressInstall')||this.installedVer.versionIsValid(this.getAttribute('version'))){var n=(typeof elementId=='string')?document.getElementById(elementId):elementId;\n";
		$c.="n.innerHTML=this.getSWFHTML();\n";
		$c.="return true}else{if(this.getAttribute('redirectUrl')!=\"\"){document.location.replace(this.getAttribute('redirectUrl'))}}return false}};\n";
		$c.="deconcept.SWFObjectUtil.getPlayerVersion=function(){var PlayerVersion=new deconcept.PlayerVersion([	0,0,0]);\n";
		$c.="if(navigator.plugins&&navigator.mimeTypes.length){var x=navigator.plugins[\"Shockwave Flash\"];\n";
		$c.="if(x&&x.description){PlayerVersion=new deconcept.PlayerVersion(x.description.replace(/([a-zA-Z]|s)+/,\"\").replace(/(s+r|s+b[0-9]+)/,\".\").split(\".\"))}}else{try{var axo=new ActiveXObject(\"ShockwaveFlash.ShockwaveFlash.7\")}catch(e){try{var axo=new ActiveXObject(\"ShockwaveFlash.ShockwaveFlash.6\");\n";
		$c.="PlayerVersion=new deconcept.PlayerVersion([6,0,21]);\n";
		$c.="axo.AllowScriptAccess=\"always\"}catch(e){if(PlayerVersion.major==6){return PlayerVersion}}try{axo=new ActiveXObject(\"ShockwaveFlash.ShockwaveFlash\")}catch(e){}}if(axo!=null){PlayerVersion=new deconcept.PlayerVersion(axo.GetVariable(\"\$version\").split(\" \")[1].split(\",\"))}}return PlayerVersion};\n";
		$c.="deconcept.PlayerVersion=function(arrVersion){this.major=arrVersion[0]!=null?parseInt(arrVersion[0]):0;\n";
		$c.="this.minor=arrVersion[1]!=null?parseInt(arrVersion[1]):0;\n";
		$c.="this.rev=arrVersion[2]!=null?parseInt(arrVersion[2]):0};\n";
		$c.="deconcept.PlayerVersion.prototype.versionIsValid=function(fv){if(this.major<fv.major)return false;\n";
		$c.="if(this.major>fv.major)return true;\n";
		$c.="if(this.minor<fv.minor)return false;\n";
		$c.="if(this.minor>fv.minor)return true;\n";
		$c.="if(this.rev<fv.rev)return false;\n";
		$c.="return true};\n";
		$c.="deconcept.util={getRequestParameter:function(param){var q=document.location.search||document.location.hash;\n";
		$c.="if(q){var pairs=q.substring(1).split(\"&\");\n";
		$c.="for(var i=0;\n";
		$c.="i<pairs.length;\n";
		$c.="i++){if(pairs[i].substring(0,pairs[i].indexOf(\"=\"))==param){return pairs[i].substring((pairs[i].indexOf(\"=\")+1))}}}return \"\"}};\n";
		$c.="deconcept.SWFObjectUtil.cleanupSWFs=function(){if(window.opera||!document.all)return;\n";
		$c.="var objects=document.getElementsByTagName(\"OBJECT\");\n";
		$c.="for(var i=0;\n";
		$c.="i<objects.length;\n";
		$c.="i++){objects[i].style.display='none';\n";
		$c.="for(var x in objects[i]){if(typeof objects[i][x]=='function'){objects[i][x]=function(){}}}}};\n";
		$c.="deconcept.SWFObjectUtil.prepUnload=function(){__flash_unloadHandler=function(){};\n";
		$c.="__flash_savedUnloadHandler=function(){};\n";
		$c.="if(typeof window.onunload=='function'){var oldUnload=window.onunload;\n";
		$c.="window.onunload=function(){deconcept.SWFObjectUtil.cleanupSWFs();\n";
		$c.="oldUnload()}}else{window.onunload=deconcept.SWFObjectUtil.cleanupSWFs}};\n";
		$c.="if(typeof window.onbeforeunload=='function'){var oldBeforeUnload=window.onbeforeunload;\n";
		$c.="window.onbeforeunload=function(){deconcept.SWFObjectUtil.prepUnload();\n";
		$c.="oldBeforeUnload()}}else{window.onbeforeunload=deconcept.SWFObjectUtil.prepUnload};\n";
		$c.="if(Array.prototype.push==null){Array.prototype.push=function(item){this[this.length]=item;\n";
		$c.="return this.length}}var getQueryParamValue=deconcept.util.getRequestParameter;\n";
		$c.="var FlashObject=deconcept.SWFObject;\n";
		$c.="var SWFObject=deconcept.SWFObject;\n";
		$c.="</script>\n";
		$c.="<div id=\"chart\" class=\"chart\">\n";
			$c.="<embed type=\"application/x-shockwave-flash\" src=\"bin/reporting/swf/fcp-bars.swf\" id=\"flashchart\" name=\"flashchart\" bgcolor=\"#ffffff\" quality=\"high\" flashvars=\"config=bin/reporting/xml/bar_".$_SESSION['sid'].".xml\" height=\"400\" width=\"500\">\n";
		$c.="</div>\n";
		$c.="<script type=\"text/javascript\">\n";
		$c.="var source = new SWFObject('bin/reporting/swf/fcp-bars.swf', 'flashchart', '550', '400', 5, \"#ffffff\");\n";
		$c.="source.addVariable(\"config\", 'bin/reporting/xml/bar_".$_SESSION['sid'].".xml');source.write(\"chart\");\n";
		$c.="</script>\n";

		return $c;

	}
}
?>