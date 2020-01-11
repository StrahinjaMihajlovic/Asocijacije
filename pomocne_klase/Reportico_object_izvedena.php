<?php
namespace app\pomocne_klase;

/**
 * Description of Reportico_object_izvedena
 *
 * @author strahinja
 */

include_once('smarty/libs/Smarty.class.php');
require_once('reportico\reportico\components\swdb.php');
require_once('reportico\reportico\components\swsql.php');
require_once('reportico\reportico\components\swutil.php');
require_once('reportico\reportico\components\swpanel.php');

// Set up globals
$g_project = false;
$g_language = "en_gb";
$g_menu = false;
$g_admin_menu = false;
$g_menu_title = false;
$g_dropdown_menu = false;
$g_translations = false;
$g_locale = false;
$g_report_desc = false;

// Defines external plugin parameters
global $g_no_sql;
global $g_external_param1;   // Values passed form calling framworks
global $g_external_param2;   
global $g_external_param3;   

// Until next release can only include a config file from a single
// project, so use this variable to ensure only a single config file
// is included
global $g_included_config;
$g_included_config = false;

$g_no_sql = false;

// Session namespace for allowing multiple reporticos on a single 
// page when called from a framework. In name space in operation the
// session array index to find reportico variables can be found in "reportico"
// otherwise it's reportic_<namespace>
global $g_session_namespace;
global $g_session_namespace_key;
$g_session_namespace = false;
$g_session_namespace_key = "reportico";


/**
 * Class reportico_object
 *
 * Base class for other reportico classes. 
 */
class Reportico_object_izvedena extends \reportico\reportico\components\reportico{
   
    function set_request_columns()
	{
		if ( array_key_exists("clearform", $_REQUEST) )
		{
			$this->clearform = true;
			$this->first_criteria_selection = true;
		}

        // If an initial set of parameter values has been set then parameters are being
        // set probably from a framework. In this case we need clear any MANUAL and HIDDEN requests
        // and set MANUAL ones from the external ones
        if ( $this->initial_execution_parameters )
        {
            foreach ( $_REQUEST as $k => $v )
                if ( preg_match ("/^MANUAL_/", $k ) || preg_match ("/^HIDDEN_/", $k ) )
                    unset($_REQUEST[$k]);
        }

        $execute_mode = $this->get_execute_mode();
		foreach ( $this->lookup_queries as $col )
		{
			// If this is first time into screen and we have defaults then
			// use these instead
			if ( get_reportico_session_param("firstTimeIn") )
			{
				$this->lookup_queries[$col->query_name]->column_value =
					$this->lookup_queries[$col->query_name]->defaults;
				if ( is_array($this->lookup_queries[$col->query_name]->column_value) )
					$this->lookup_queries[$col->query_name]->column_value =
						implode(",", $this->lookup_queries[$col->query_name]->column_value);
                // Daterange defaults needs to  eb converted to 2 values
                if ( $this->lookup_queries[$col->query_name]->criteria_type == "DATERANGE" && !$this->lookup_queries[$col->query_name]->defaults)
                {
                    $this->lookup_queries[$col->query_name]->defaults = array();
                    $this->lookup_queries[$col->query_name]->defaults[0] = "TODAY-TODAY";
                    $this->lookup_queries[$col->query_name]->defaults[1] = "TODAY";
                    $this->lookup_queries[$col->query_name]->column_value = "TODAY-TODAY";
                }
                if ( $this->lookup_queries[$col->query_name]->criteria_type == "DATE" && !$this->lookup_queries[$col->query_name]->defaults)
                {
                    $this->lookup_queries[$col->query_name]->defaults = array();
                    $this->lookup_queries[$col->query_name]->defaults[0] = "TODAY";
                    $this->lookup_queries[$col->query_name]->defaults[1] = "TODAY";
                    $this->lookup_queries[$col->query_name]->column_value = "TODAY";
                }
                $this->defaults = $this->lookup_queries[$col->query_name]->defaults;
                if ( isset($this->defaults) )
                {
                    if ( $this->lookup_queries[$col->query_name]->criteria_type == "DATERANGE" )
                    {
                        if ( !convert_date_range_defaults_to_dates("DATERANGE", 
                            $this->lookup_queries[$col->query_name]->column_value, 
                            $this->lookup_queries[$col->query_name]->column_value,
                            $this->lookup_queries[$col->query_name]->column_value2) )
                            trigger_error( "Date default '".$this->defaults[0]."' is not a valid date range. Should be 2 values separated by '-'. Each one should be in date format (e.g. yyyy-mm-dd, dd/mm/yyyy) or a date type (TODAY, TOMMORROW etc", E_USER_ERROR );
                    }
                    if ( $this->lookup_queries[$col->query_name]->criteria_type == "DATE" )
                    {
                        $dummy="";
                        if ( !convert_date_range_defaults_to_dates("DATE", $this->defaults[0], $this->range_start, $dummy) )
                        if ( !convert_date_range_defaults_to_dates("DATE", 
                            $this->lookup_queries[$col->query_name]->column_value, 
                            $this->lookup_queries[$col->query_name]->column_value,
                            $this->lookup_queries[$col->query_name]->column_value2) )
                        trigger_error( "Date default '".$this->defaults[0]."' is not a valid date. Should be in date format (e.g. yyyy-mm-dd, dd/mm/yyyy) or a date type (TODAY, TOMMORROW etc", E_USER_ERROR );
                    }
                }
			}
		}


		if ( array_key_exists("clearform", $_REQUEST) )
		{
			set_reportico_session_param("firstTimeIn",true);
		}

        // Set up show option check box settings

        // If initial form style specified use it
        if ( $this->initial_output_style ) set_reportico_session_param("target_style", $this->initial_output_style );

        // If default starting "show" setting provided by calling framework then use them
        if ( $this->show_print_button ) set_reportico_session_param("show_print_button", ( $this->show_print_button == "show" ));
        if ( $this->show_refresh_button ) set_reportico_session_param("show_refresh_button", ( $this->show_refresh_button == "show" ));
        if ( $this->initial_show_detail ) set_reportico_session_param("target_show_detail",( $this->initial_show_detail == "show" ));
        if ( $this->initial_show_graph ) set_reportico_session_param("target_show_graph",( $this->initial_show_graph == "show" ));
        if ( $this->initial_show_group_headers ) set_reportico_session_param("target_show_group_headers",( $this->initial_show_group_headers == "show" ));
        if ( $this->initial_show_group_trailers ) set_reportico_session_param("target_show_group_trailers",( $this->initial_show_group_trailers == "show" ));
        if ( $this->initial_show_criteria ) set_reportico_session_param("target_show_criteria",( $this->initial_show_criteria == "show" ));

	    $this->target_show_detail = session_request_item("target_show_detail", true, !isset_reportico_session_param("target_show_detail"));
	    $this->target_show_graph = session_request_item("target_show_graph", true, !isset_reportico_session_param("target_show_graph"));
	    $this->target_show_group_headers = session_request_item("target_show_group_headers", true, !isset_reportico_session_param("target_show_group_headers"));
	    $this->target_show_group_trailers = session_request_item("target_show_group_trailers", true, !isset_reportico_session_param("target_show_group_trailers"));
	    $this->target_show_criteria = session_request_item("target_show_criteria", true, !isset_reportico_session_param("target_show_criteria"));

		if ( get_reportico_session_param("firstTimeIn") 
                && !$this->initial_show_detail && !$this->initial_show_graph && !$this->initial_show_group_headers 
                && !$this->initial_show_group_trailers && !$this->initial_show_column_headers && !$this->initial_show_criteria 
        )
        {
            // If first time in default output hide/show elements to what is passed in URL params .. if none supplied show all
            if ( $this->execute_mode == "EXECUTE" )
            {
	                $this->target_show_detail = get_request_item("target_show_detail", false);
	                $this->target_show_graph = get_request_item("target_show_graph", false);
	                $this->target_show_group_headers = get_request_item("target_show_group_headers", false);
	                $this->target_show_group_trailers = get_request_item("target_show_group_trailers", false);
	                $this->target_show_criteria = get_request_item("target_show_criteria", false);
                    if ( !$this->target_show_detail && !$this->target_show_graph && !$this->target_show_group_headers
                        && !$this->target_show_group_trailers && !$this->target_show_column_headers && !$this->target_show_criteria )
                    {
                            $this->target_show_detail = true;
                            $this->target_show_graph = true;
                            $this->target_show_group_headers = true;
                            $this->target_show_group_trailers = true;
                            $this->target_show_criteria = true;
                    }
	                set_reportico_session_param("target_show_detail",$this->target_show_detail);
	                set_reportico_session_param("target_show_graph",$this->target_show_graph);
	                set_reportico_session_param("target_show_group_headers",$this->target_show_group_headers);
	                set_reportico_session_param("target_show_group_trailers",$this->target_show_group_trailers);
	                set_reportico_session_param("target_show_criteria",$this->target_show_criteria);
            }
            else
            {
                    //$this->target_show_detail = true;
                    //$this->target_show_graph = true;
                    //$this->target_show_group_headers = true;
                    //$this->target_show_group_trailers = true;
                    //$this->target_show_column_headers = true;
                    //$this->target_show_criteria = true;
                    //set_reportico_session_param("target_show_detail",true);
                    //set_reportico_session_param("target_show_graph",true);
                    //set_reportico_session_param("target_show_group_headers",true);
                    //set_reportico_session_param("target_show_group_trailers",true);
                    //set_reportico_session_param("target_show_column_headers",true);
                    //set_reportico_session_param("target_show_criteria",true);
            }
        }
        else
        {
            // If not first time in, then running report would have come from
            // prepare screen which provides details of what report elements to include
            if ( $this->execute_mode == "EXECUTE" )
            {
	            $runfromcriteriascreen = get_request_item("user_criteria_entered", false);
                if ( $runfromcriteriascreen )
                {
	                $this->target_show_detail = get_request_item("target_show_detail", false);
	                $this->target_show_graph = get_request_item("target_show_graph", false);
	                $this->target_show_group_headers = get_request_item("target_show_group_headers", false);
	                $this->target_show_group_trailers = get_request_item("target_show_group_trailers", false);
	                $this->target_show_criteria = get_request_item("target_show_criteria", false);
                    if ( !$this->target_show_detail && !$this->target_show_graph && !$this->target_show_group_headers
                        && !$this->target_show_group_trailers && !$this->target_show_column_headers && !$this->target_show_criteria )
                    {
                            $this->target_show_detail = true;
                            $this->target_show_graph = true;
                            $this->target_show_group_headers = true;
                            $this->target_show_group_trailers = true;
                            $this->target_show_criteria = true;
                    }
	                set_reportico_session_param("target_show_detail",$this->target_show_detail);
	                set_reportico_session_param("target_show_graph",$this->target_show_graph);
	                set_reportico_session_param("target_show_group_headers",$this->target_show_group_headers);
	                set_reportico_session_param("target_show_group_trailers",$this->target_show_group_trailers);
	                set_reportico_session_param("target_show_criteria",$this->target_show_criteria);
                }
            }
        }
        if ( isset ( $_REQUEST["target_show_detail"] ))  set_reportico_session_param("target_show_detail",$_REQUEST["target_show_detail"]);
        if ( isset ( $_REQUEST["target_show_graph"] ))  set_reportico_session_param("target_show_graph",$_REQUEST["target_show_graph"]);
        if ( isset ( $_REQUEST["target_show_group_headers"] ))  set_reportico_session_param("target_show_group_headers",$_REQUEST["target_show_group_headers"]);
        if ( isset ( $_REQUEST["target_show_group_trailers"] ))  set_reportico_session_param("target_show_group_trailers",$_REQUEST["target_show_group_trailers"]);
        if ( isset ( $_REQUEST["target_show_criteria"] ))  set_reportico_session_param("target_show_criteria",$_REQUEST["target_show_criteria"]);

		if ( array_key_exists("clearform", $_REQUEST) )
		{
			return;
		}


		// Fetch current criteria choices from HIDDEN_ section
		foreach ( $this->lookup_queries as $col )
		{
			// criteria name could be a field name or could be "groupby" or the like
			$crit_name   = $col->query_name;
			$crit_value  = null;

			if ( array_key_exists($crit_name, $_REQUEST) )
			{
                // Since using Select2, we find unselected list boxes still send an empty array with a single character which we dont want to include
                // as a criteria selection
                if ( !(is_array($_REQUEST[$col->query_name]) && count($col->query_name) == 1 && $_REQUEST[$col->query_name][0] == "" ))
				    $crit_value = $_REQUEST[$crit_name];
			}

			if ( array_key_exists("HIDDEN_" . $crit_name, $_REQUEST) )
			{
                                $crit_value = $_REQUEST["HIDDEN_" . $crit_name];
                        }

			// applying multi-column values
			if ( array_key_exists("HIDDEN_" . $crit_name . "_FROMDATE", $_REQUEST) )
			{
				$crit_value_1 = $_REQUEST["HIDDEN_" . $crit_name . "_FROMDATE"];
				$this->lookup_queries[$crit_name]->column_value1 = $crit_value_1;
			}

			if ( array_key_exists("HIDDEN_" . $crit_name . "_TODATE", $_REQUEST) )
			{
				$crit_value_2 = $_REQUEST["HIDDEN_" . $crit_name . "_TODATE"];
				$this->lookup_queries[$crit_name]->column_value2 = $crit_value_2;
			}
			// end applying multi-column values

			if ( array_key_exists("EXPANDED_" . $crit_name, $_REQUEST) )
			{
				$crit_value = $_REQUEST["EXPANDED_" . $crit_name];
			}


			// in case of single column value, we apply it now
			if ( !is_null( $crit_value ) )
			{
				$this->lookup_queries[$crit_name]->column_value = $crit_value;

				 // for groupby criteria, we need to show and hide columns accordingly
                                 if ($crit_name == 'showfields' || $crit_name == 'groupby')
                                 {
					foreach ( $this->columns as $q_col)
					{
						//show the column if it matches a groupby value
						if  ( in_array ( $q_col->column_name, $crit_value ) )
						{
                                                        $q_col->attributes['column_display'] = "show";
						}
						// if it doesn't match, hide it if this is the first 
						// groupby column we are going through; otherwise
						// leave it as it is
						elseif ( !isset ( $not_first_pass ) )
						{
							$q_col->attributes['column_display'] = "hide";
						}
					}
					$not_first_pass = true;
                                }
			}
		}

		// Fetch current criteria choices from MANUAL_ section
		foreach ( $this->lookup_queries as $col )
		{
            $identified_criteria = false;

            // If an initial set of parameter values has been set then parameters are being
            // set probably from a framework. Use these for setting criteria
            if ( $this->initial_execution_parameters )
            {
                if ( isset($this->initial_execution_parameters[$col->query_name]) )
                {
                    $val1 = false;
                    $val2 = false;
                    $criteriaval = $this->initial_execution_parameters[$col->query_name];
                    if ( $col->criteria_type == "DATERANGE" )
                    {
                        if ( !convert_date_range_defaults_to_dates("DATERANGE", 
                            $criteriaval,
                            $val1,
                            $val2) )
                            trigger_error( "Date default '".$criteriaval."' is not a valid date range. Should be 2 values separated by '-'. Each one should be in date format (e.g. yyyy-mm-dd, dd/mm/yyyy) or a date type (TODAY, TOMMORROW etc", E_USER_ERROR );
                        else
                        {
                            $_REQUEST["MANUAL_".$col->query_name."_FROMDATE"] = $val1;
                            $_REQUEST["MANUAL_".$col->query_name."_TODATE"] = $val2;
		                    if ( get_reportico_session_param('latestRequest') )
                            {
                                $_SESSION[reportico_namespace()]["latestRequest"]["MANUAL_".$col->query_name."_FROMDATE"] = $val1;
                                $_SESSION[reportico_namespace()]["latestRequest"]["MANUAL_".$col->query_name."_TODATE"] = $val2;
                            }
                        }
                    }
                    else if ( $col->criteria_type == "DATE" )
                    {
                        if ( !convert_date_range_defaults_to_dates("DATERANGE", 
                            $criteriaval,
                            $val1,
                            $val2) )
                            trigger_error( "Date default '".$criteriaval."' is not a valid date. Should be in date format (e.g. yyyy-mm-dd, dd/mm/yyyy) or a date type (TODAY, TOMMORROW etc", E_USER_ERROR );
                        else
                        {
                            $_REQUEST["MANUAL_".$col->query_name."_FROMDATE"] = $val1;
                            $_REQUEST["MANUAL_".$col->query_name."_TODATE"] = $val1;
                            $_REQUEST["MANUAL_".$col->query_name] = $val1;
			                if ( get_reportico_session_param('latestRequest') )
                            {
                                $_SESSION[reportico_namespace()]["latestRequest"]["MANUAL_".$col->query_name."_FROMDATE"] = $val1;
                                $_SESSION[reportico_namespace()]["latestRequest"]["MANUAL_".$col->query_name."_TODATE"] = $val1;
                                $_SESSION[reportico_namespace()]["latestRequest"]["MANUAL_".$col->query_name] = $val1;
                            }
                        }
                    }
                    else
                    {
                        $_REQUEST["MANUAL_".$col->query_name] = $criteriaval;
		                if ( get_reportico_session_param('latestRequest') )
                        {
                            $_SESSION[reportico_namespace()]["latestRequest"]["MANUAL_".$col->query_name] = $criteriaval;
                        }
                    }
                }
            }

            // Fetch the criteria value summary if required for displaying
            // the criteria entry summary at top of report
			if ( $execute_mode && $execute_mode != "MAINTAIN" && $this->target_show_criteria &&
                    ( ( array_key_exists($col->query_name, $_REQUEST) && !(is_array($_REQUEST[$col->query_name]) && count($col->query_name) == 1 && $_REQUEST[$col->query_name][0] == "" ))
			        || array_key_exists("MANUAL_".$col->query_name, $_REQUEST) 
			        || array_key_exists("HIDDEN_".$col->query_name, $_REQUEST) 
                    ) )
			{
				$lq =&	$this->lookup_queries[$col->query_name] ;
                if ( $lq->criteria_type == "LOOKUP" )
				    $lq->execute_criteria_lookup();
	            $lq->criteria_summary_display();
                $identified_criteria = true;
            }

			if ( array_key_exists($col->query_name, $_REQUEST) )
			{
                // Since using Select2, we find unselected list boxes still send an empty array with a single character which we dont want to include
                // as a criteria selection
                if ( !(is_array($_REQUEST[$col->query_name]) && count($col->query_name) == 1 && $_REQUEST[$col->query_name][0] == "") )
				    $this->lookup_queries[$col->query_name]->column_value =
					    $_REQUEST[$col->query_name];
			}

			if ( array_key_exists("MANUAL_".$col->query_name, $_REQUEST) )
			{
				$this->lookup_queries[$col->query_name]->column_value =
				$_REQUEST["MANUAL_".$col->query_name];

				$lq =&	$this->lookup_queries[$col->query_name] ;
				if ( $lq->criteria_type == "LOOKUP" && $_REQUEST["MANUAL_".$col->query_name])
				{
                    if ( array_key_exists("MANUAL_".$col->query_name, $_REQUEST) )
					foreach ( $lq->lookup_query->columns as $k => $col1 )
					{
						if ( $col1->lookup_display_flag )
							$lab =& $lq->lookup_query->columns[$k];
						if ( $col1->lookup_return_flag )
							$ret =& $lq->lookup_query->columns[$k];
						if ( $col1->lookup_abbrev_flag )
							$abb =& $lq->lookup_query->columns[$k];
					}

					if ( $abb && $ret && $abb->query_name != $ret->query_name )
					{
                        if ( !$identified_criteria )
						    $lq->execute_criteria_lookup();
						$res =& $lq->lookup_query->targets[0]->results;
						$choices = $lq->column_value;
						if ( !is_array($choices) )
							$choices = explode(',', $choices);
						$lq->column_value;
						$choices = array_unique($choices);
						$target_choices = array();
						foreach ( $choices as $k => $v )
					   	{
                                if ( isset ( $res[$abb->query_name] ) )
								foreach ( $res[$abb->query_name] as $k1 => $v1 )
								{
									//echo "$v1 / $v<br>";
									if ( $v1 == $v )
									{
										$target_choices[] = $res[$ret->query_name][$k1];
										//echo "$k -> ".$choices[$k]."<BR>";
									}
								}
						}	
						$choices = $target_choices;
						$lq->column_value = implode(",", $choices);

						if ( !$choices )
						{
							// Need to set the column value to a arbitrary value when no data found
							// matching users MANUAL entry .. if left blank then would not bother 
							// creating where clause entry
							$lq->column_value = "(NOTFOUND)";
						}
						$_REQUEST["HIDDEN_".$col->query_name] = $choices;
					}
					else
					{
						if ( !is_array($_REQUEST["MANUAL_".$col->query_name]))
							$_REQUEST["HIDDEN_".$col->query_name] = explode(",", $_REQUEST["MANUAL_".$col->query_name]);
						else
							$_REQUEST["HIDDEN_".$col->query_name] = $_REQUEST["MANUAL_".$col->query_name];
					}
				}
			}

			if ( array_key_exists($col->query_name."_FROMDATE_DAY", $_REQUEST) )
			{
				$this->lookup_queries[$col->query_name]->column_value =
					$this->lookup_queries[$col->query_name]->collate_request_date(
						$col->query_name, "FROMDATE",
						$this->lookup_queries[$col->query_name]->column_value,
						SW_PREP_DATEFORMAT);
			}

			if ( array_key_exists($col->query_name."_TODATE_DAY", $_REQUEST) )
			{
				$this->lookup_queries[$col->query_name]->column_value2 =
					$this->lookup_queries[$col->query_name]->collate_request_date(
						$col->query_name, "TODATE", 
						$this->lookup_queries[$col->query_name]->column_value2,
						SW_PREP_DATEFORMAT);
			}

			if ( array_key_exists("MANUAL_".$col->query_name."_FROMDATE", $_REQUEST) )
			{
				$this->lookup_queries[$col->query_name]->column_value =
					$_REQUEST["MANUAL_".$col->query_name."_FROMDATE"];

			}

			if ( array_key_exists("MANUAL_".$col->query_name."_TODATE", $_REQUEST) )
            {
				$this->lookup_queries[$col->query_name]->column_value2 =
					$_REQUEST["MANUAL_".$col->query_name."_TODATE"];
            }

			if ( array_key_exists("EXPANDED_".$col->query_name, $_REQUEST) )
				$this->lookup_queries[$col->query_name]->column_value =
					$_REQUEST["EXPANDED_".$col->query_name];
		}


        // If external page has supplied an initial output format then use it
        if ( $this->initial_output_format )
            $_REQUEST["target_format"] = $this->initial_output_format;

        // If printable HTML requested force output type to HTML
        if ( get_request_item("printable_html") )
        {
            $_REQUEST["target_format"] = "HTML";
        }

		// Prompt user for report destination if target not already set - default to HTML if not set
		if ( !array_key_exists("target_format", $_REQUEST) && $execute_mode == "EXECUTE" )
			$_REQUEST["target_format"] = "HTML";
			
		if ( array_key_exists("target_format", $_REQUEST) && $execute_mode == "EXECUTE" && count($this->targets) == 0)
		{	
			$tf = $_REQUEST["target_format"];
            if ( isset ( $_GET["target_format"] ) )
			    $tf = $_GET["target_format"];
			$this->target_format = strtolower($tf);

            if ( $this->target_format == "pdf" )
            {
                $this->pdf_engine_file = "reportico_report_{$this->pdf_engine}.php";
			    require_once($this->pdf_engine_file);
            }
            else
			    require_once("reportico_report_".$this->target_format.".php");
			$this->target_format = strtoupper($tf);
			switch ( $tf )
			{
				case "CSV" :
				case "csv" :
				case "Microsoft Excel" :
				case "EXCEL" :
					$rep = new reportico_report_csv();
					$this->add_target($rep);
					$rep->set_query($this);
					break;

				case "soap" :
				case "SOAP" :
					$rep = new reportico_report_soap_template();
					$this->add_target($rep);
					$rep->set_query($this);
					break;

				case "html" :
				case "HTML" :
					$rep = new reportico_report_html();
					$this->add_target($rep);
					$rep->set_query($this);
					break;

				case "htmlgrid" :
				case "HTMLGRID" :
					$rep = new reportico_report_html_grid_template();
					$this->add_target($rep);
					$rep->set_query($this);
					break;

				case "pdf" :
				case "PDF" :
                    if ( $this->pdf_engine == "tcpdf" )
					    $rep = new reportico_report_tcpdf();
                    else
					    $rep = new reportico_report_fpdf();
					$rep->page_length = 80;
					$this->add_target($rep);
					$rep->set_query($this);
					break;

				case "json" :
				case "JSON" :
					$rep = new reportico_report_json();
					$rep->page_length = 80;
					$this->add_target($rep);
					$rep->set_query($this);
					break;

               case "jquerygrid" :
               case "JQUERYGRID" :
                       $rep = new reportico_report_jquerygrid();
                       $rep->page_length = 80;
                       $this->add_target($rep);
                       $rep->set_query($this);
                    break;

				case "xml" :
				case "XML" :
					$rep = new reportico_report_xml();
					$rep->page_length = 80;
					$this->add_target($rep);
					$rep->set_query($this);
					break;

				//case "array" :
				case "ARRAY" :
					$rep = new reportico_report_array();
					$rep->page_length = 80;
					$this->add_target($rep);
					$rep->set_query($this);
					break;

				default:
					// Should not get here
			}
		}

		if ( array_key_exists("mailto", $_REQUEST) )
		{
			$this->email_recipients = $_REQUEST["mailto"];
		}

	}
}
