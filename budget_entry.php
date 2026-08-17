<?php @session_start();
if(!isset($_SESSION['userLogin']) and !isset($_SESSION['login_id']) and !isset($_SESSION['login_status']) and !isset($_SESSION['role']) and $_SESSION['userLogin']!='ok')
   {
	   echo "<script>location='index.php';</script>";
   }
    $r_vals=@base64_decode($_REQUEST['r_val']);
	$memo_id=@base64_decode($_REQUEST['id']);
$role=@$_SESSION['role'];
$login_status=@$_SESSION['login_status'];
 $login_id=@$_SESSION['login_id'];
 $login_id_base=@base64_encode($login_id);
 //$role=@$_SESSION['role'];
 $staff_category=@$_SESSION['staff_category'];

?>
<!DOCTYPE html>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION['project_title'];?></title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<style>
	.date1 {
		color: #333333;
		background: #F7F7F7;
		border: 1px solid #CCCCCC;
		height:25px;
		width:200px;
		vertical-align:inherit;
		text-align:inherit;
		padding-left: 1px;
		font-size:12px;
		font-family:Palatino Linotype;
	}

	.delete{
		content: 'X';
		border:outset 1px red;
		border-radius:4px;
		padding:3px;
		cursor:pointer;
	}
</style>
	
<!--
Template 2036 Blue Office
http://www.tooplate.com/view/2036-blue-office
-->
<?php include("required_jQuery_files.php");
include "function.php";?>

<link href="tooplate_style.css" rel="stylesheet" type="text/css" />

<script>
$(document).ready(function(){
	$("#trCapitals").hide();
	
	 ////$(".iframe").colorbox({iframe:true, width:"80%", height:"80%"});
     ////$('#ppvno').hide();
	var max_fields = 50;
	var wrapper = $(".container1");
	var add_button = $(".add_form_field");
	var x = 1;

	$('#dgout').datagrid({
		onSelect: function(index, row){
			document.getElementById("budgetcode").value=row.folio_code;
			//alert(row.folio_code);
			//$(this).datagrid('beginEdit', index);
			//var ed = $(this).datagrid('getEditor', {index:index,field:field});
			//$(ed.target).focus();
			///getSelections();
			
        //e.preventDefault();
		/*if(x < max_fields){
			x++;
			//$(wrapper).append('<div><input type="text" name="folio[]" /><a href="#" class="delete">Delete</a></div>'); //add input box
			//$(wrapper).append('<tr style="font-size:12px"><td><a href="#" class="delete">Delete</a> | ' + x + '</td><td nowrap><input type="search" name="bcode[]" value=""></td><td style="font-size:12px"><span id="folio_desc' + x + '"></span></td><td width="5%"><input type="number" class="amt2" name="dr_bamt[]" onblur="sum2(\'amt2\')" style="background-color: #FEFFB0;font-weight: bold;text-align: right;"></td><td width="5%"><input type="number" class="amt3" name="cr_bamt[]" onblur="sum2(\'amt3\')" style="background-color: #FEFFB0;font-weight: bold;text-align: right;"></td></tr>');
			$(".rw").before('<tr style="font-size:12px">' +
				'<td><a href="#" class="delete"><strong>X</strong></a></td><td nowrap><input type="text" name="folio[]" value="' + row.folio_code + '" readonly size="11" maxlength="11"></td>' +
				'<td style="font-size:12px"><div id="foliodesc' + x + '">' + row.title + '<input type="hidden" name="fdesc[]" value="' + row.title + '"></div></td>' +
				'<td width="5%"><input type="number" class="amt2" name="dr_bamt[]" onblur="sum2(\'amt2\')" style="background-color: #FEFFB0;font-weight: bold;text-align: right;"></td></tr>');
			
			//.insertAfter($(this).closest('tr'));
		}else{
			alert('You reached the limits');
		}*/
    
		}
	});
	
	$('#dgstaff').datagrid({
		onSelect: function(index, row){
			//alert(row.fileno);
			//$(this).datagrid('beginEdit', index);
			//var ed = $(this).datagrid('getEditor', {index:index,field:field});
			//$(ed.target).focus();
			//getSelectedStaff();
			//alert('Staff ID:'+row.fileno);
				document.getElementById('fileno').value=row.fileno;
				document.getElementById('name').value=row.fullname;
				document.getElementById('act_no').value=row.acct_no;
				document.getElementById('bank').value=row.bank_name;
				document.getElementById('address').value=row.dept;
				document.getElementById('phoneno').value=row.phone_no;
				document.getElementById('load_voucher_fileno').innerHTML=row.fileno;
		}
	});

	$(add_button).click(function(e) {
        e.preventDefault();
		if(x < max_fields){
			x++;
			//$(wrapper).append('<div><input type="text" name="folio[]" /><a href="#" class="delete">Delete</a></div>'); //add input box
			//$(wrapper).append('<tr style="font-size:12px"><td><a href="#" class="delete">Delete</a> | ' + x + '</td><td nowrap><input type="search" name="bcode[]" value=""></td><td style="font-size:12px"><span id="folio_desc' + x + '"></span></td><td width="5%"><input type="number" class="amt2" name="dr_bamt[]" onblur="sum2(\'amt2\')" style="background-color: #FEFFB0;font-weight: bold;text-align: right;"></td><td width="5%"><input type="number" class="amt3" name="cr_bamt[]" onblur="sum2(\'amt3\')" style="background-color: #FEFFB0;font-weight: bold;text-align: right;"></td></tr>');
			$(".rw").before('<tr style="font-size:12px">' +
				'<td><a href="#" class="delete"><strong>X</strong></a></td>' +
				'<td nowrap><input type="text" name="folio[]" value="" size="11" maxlength="11" readonly></td>' +
				'<td style="font-size:12px"><div id="foliodesc' + x + '"><input type="hidden" name="fdesc[]" value="null"></div></td>' + 
				'<td width="5%"><input type="number" class="amt2" name="dr_bamt[]" onblur="sum2(\'amt2\')" onChange="sum2(\'amt2\')" style="background-color: #FEFFB0;font-weight: bold;text-align: right;"></td></tr>');
			
			//.insertAfter($(this).closest('tr'));
		}else{
			alert('You reached the limits');
		}
    });
	$(wrapper).on("click", ".delete", function(e){
		e.preventDefault();
		$(this).closest('tr').remove();
		$("#dr_vamount").val(0);
		$("#vamount").val(0);
		x--;
	});


});

function sum(){
    //iterate through each textboxes and add keyup
        //handler to trigger sum event
        $(".amt").each(function() {
 
            //$(this).keyup(function(){
                calculateSum();
            //});
        });
  }
  function calculateSum() {
 
        var sum = 0, totalamt=0;
		var vamount=parseFloat($("#vamount").val());
        //iterate through each textboxes and add the values
        $(".amt").each(function() {
 
            //add only if the value is number
            if(!isNaN(this.value) && this.value.length!=0) {
                sum += parseFloat(this.value);
            }
 
        });
        //.toFixed() method will roundoff the final sum to 2 decimal places
		totalamt= vamount - sum;
        $("#total").html(totalamt.toFixed(2));
		$("#total_deduction").html('('+sum.toFixed(2)+')');
    }
function sum2(clv){
    //iterate through each textboxes and add keyup
        //handler to trigger sum event
		var classid="."+clv;
        $(classid).each(function() {
 
            //$(this).keyup(function(){
              calculateSum2(clv);
            //});
        });
  }
 function calculateSum2(clv) {
 		var classid="."+clv;
        var sum = 0, totalamt=0;
		var vamount=parseFloat($("#vamount").val());
        //iterate through each textboxes and add the values
        $(classid).each(function() {
 
            //add only if the value is number
            if(!isNaN(this.value) && this.value.length!=0) {
                sum += parseFloat(this.value);
            }
 
        });
        //.toFixed() method will roundoff the final sum to 2 decimal places
		//totalamt= vamount - sum;
		totalamt= sum;
		$(".t_" + clv).val(totalamt);
      $("#total").html(totalamt.toFixed(2));
	  $("#vamount").val(totalamt);
	//	$("#total_deduction").html('('+sum.toFixed(2)+')');
    } 
function do_total(ctl,v,sn)
 {
	 //alert(ctl+" V:"+v);
	 if($("#vamount").val()=='')
	  {
		  alert("Error: Payment amount has not been entered");
		  document.getElementById('code'+sn).checked=false;
		  exit();
	  }
	  
	 var v_set=""; var folio_c=""; var rate_v=0;
	 v_set = v.split("***");
	 folio_c = v_set[0]; rate_v=parseFloat(v_set[1]);
	 //alert(ctl+" "+v+" SN:"+sn);
	 //alert("CONTROL:"+ ctl+" FOLIO CODE:"+folio_c+" Rate:"+rate_v+" CODE1 : "+document.getElementById(ctl).value);
	 if(document.getElementById('code'+sn).checked==true)
	  { document.getElementById(ctl).value=rate_v/100 * parseFloat($("#vamount").val());
	    $("#amount2"+sn).html('('+document.getElementById(ctl).value+')').show();
	  }
	 else
	   { 
	     document.getElementById(ctl).value="";
		 $("#amount2"+sn).html('').show();
	   }
	 
	 sum();
 }

function display_total()
{
	var amt=$("#vamount").val();
	$("#total").html(amt).show();
}


function swapcontent(cv,v,a,b,c,d,e,f,g,h,i,j,k,l)
{ 
	var divid="#"+cv;
	$(divid).html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
	var url="scriptfile_y.php";
	var str;
	
	if(cv=='budget_section')
	{
		var mydata = JSON.stringify($('form').serializeObject());
		$.post(url,{contentvar:cv,action:v, r_id:a, mydata:mydata},function(data){
			$(divid).html(data).show(); 
			$("#roll").html('').show();
		});
	}
	if(cv=='budget_capital')
	{
		var mydata = (JSON.stringify($('#frm2').serializeObject()));
		$.post(url,{contentvar:cv,action:v, r_id2:a, mydata:mydata},function(data){
			$(divid).html(data).show();
			$("#roll").html('').show();
		});
	}
	if(cv=='budget_breakdown')
	{
		var mydata = (JSON.stringify($('form').serializeObject()));
		$.post(url,{contentvar:cv,action:v, r_id2:a, mydata:mydata},function(data){
			$(divid).html(data).show();
			$("#roll").html('').show();
		});
	}

	if(cv=='load_code')
	{
		$.post(url,{contentvar:cv, dept_code:v},function(data){
			$(divid).html(data).show();
			$("#roll").html('').show();
		});
	}
	if(cv='budget_folio')
	{
		$.post(url,$("#frm").serialize()+"&contentvar="+cv,function(data){
			$(divid).html(data).show();
			$("#roll").html('').show();
		});
	}
	if(cv=='load_folio') //start of load folio
	{
		var tab = $('#tt').tabs('getSelected');
		var index = $('#tt').tabs('getTabIndex', tab);
		var test = (JSON.stringify($('#frm2').serializeObject()));
		if(v == "" || a == "")
		{
			$(divid).html('').show();  //stop loader from rolling
			$(divid).hide();
			exit();
		} //end of validation 
		var test = (JSON.stringify($('#frm2').serializeObject()));
		$.post(url,$("#frm2").serialize()+"&contentvar="+cv+"&tabindex="+index,function(data){
			$(divid).html(data).show();
		});
	}
	
	if(cv=='another')
	{
		$.post(url,$("form").serialize()+"&contentvar="+cv,function(data){
			$(divid).html(data).show();
		});
	}
} //end of swapcontent


 </script>

<script>
function reload_folio(){
		
	  var fundcenter=$("#fundsource").val();
	  var deptcode=$("#funddept").val();
	  var category=$("#fundcat").val();
	$('#folio').combogrid('grid').datagrid('load', {category:category, fundcenter:fundcenter, deptcode:deptcode});
}
</script> 
</head>
<body class="subpage"><!-- onLoad="$('#fileno').combogrid('StaffGrid').datagrid('load', {filen:''});"-->

<div id="tooplate_wrapper">

	<div id="tooplate_sidebar">
	<?php include_once("sidebar_main.php"); ?>
    </div> <!-- end of sidebar tooplate_sidebar-->
	
    <div id="tooplate_main">
    	
        <div id="tooplate_menu">
            <?php include_once("menu_main.php"); ?>
        </div> <!-- end of tooplate_menu -->
        
        <div id="content_title_box">
	        <h2>Raise Voucher</h2>
                <p>&nbsp;</p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
              <div class="easyui-panel" title="Budget Entry" style="width:800px">     
		<div style="padding:10px 60px 20px 60px">             
               <form enctype="multipart/form-data">
		  <table border="0" cellpadding="0" cellspacing="0" class="vch">
              <tr>
			    <th width="18%" align="left" valign="middle" height="33">BUDGET CATEGORY:</th>
			    <td width="82%" align="left" valign="middle" height="33"><select name="bcat2" id="bcat2" style="width:300px"  onChange="if($('#bcat2 option:selected').val() == 'Departmental'){ $('tr#exp_tr').hide(); $('tr#fund_tr').hide(); } else { $('tr#exp_tr').show(); $('tr#fund_tr').show(); } 
                if($('#bcat2 option:selected').val() == 'Recurrent' || $('#bcat2 option:selected').val() == 'Departmental'){ $('#trCapitals').hide(); $('#trSubCat').show(); }else{ $('#trCapitals').show(); $('#trSubCat').hide(); }">
			      <option selected="selected" value="">Select item...</option>
			      <?php if(isset($_REQUEST['faculty'])){ ?>
					<option value='Departmental'>Departmental</option>
				<?php }else{ ?>
					<option value='Departmental'>Departmental</option>
					<option value='Recurrent'>Recurrent</option>
					<option value='IGR Capital'>IGR Capital</option>
					<option value='TETFund Capital'>TETFund Capital</option>
					<option value='TETFund Research'>TETFund Research</option>
					<option value='TETFund Training'>TETFund Training</option>
					<option value='NEEDS Assessment'>NEEDS Assessment</option>
					<option value='FG Capital'>FG Capital</option>
					<option value='Refund'>Refund</option>
					<option value='Others'>Others</option>
				<?php } ?>
			      </select></td>
		      </tr>
              <tr id="trSubCat">
			    <th width="18%" align="left" valign="middle" height="33" nowrap>BUDGET SUB CATEGORY:</th>
			    <td width="82%" align="left" valign="middle" height="33"><select name="bsubcat" id="bsubcat" style="width:300px">
			      <option selected="selected" value="">Select item...</option>
                  <option value='Administrative and General Expenditure'>Administrative and General Expenditure</option>
                  <option value='General Academic Expenses'>General Academic Expenses</option>
			      <option value='Students Services Cost (Over Head)'>Students Services Cost (Over Head)</option>
			      <option value='General Maintenance Cost'>General Maintenance Cost</option>
			      <option value='Finance Management Cost (Over Head)'>Finance Management Cost (Over Head)</option>
			      <option value='Staff Development'>Staff Development</option>
			      <option value='Faculty of Agriculture'>Faculty of Agriculture</option>
			      <option value='Faculty of Arts'>Faculty of Arts</option>
                  <option value='Faculty of Management Sciences'>Faculty of Management Sciences</option>
                  <option value='Faculty of Social Sciences'>Faculty of Social Sciences</option>
                  <option value='Faculty of Communication and Information Science'>Faculty of Communication and Information Science</option>
                  <option value='Faculty of Education'>Faculty of Education</option>
                  <option value='Faculty of Engineering and Technology'>Faculty of Engineering and Technology</option>
                  <option value='Faculty of Health Sciences'>Faculty of Health Sciences</option>
                  <option value='Faculty of Basic Medical Sciences'>Faculty of Basic Medical Sciences</option>
                  <option value='Faculty of Basic Clinical Sciences'>Faculty of Basic Clinical Sciences</option>
                  <option value='Faculty of Clinical Sciences'>Faculty of Clinical Sciences</option>
                  <option value='Faculty of Pharmaceutical Sciences'>Faculty of Pharmaceutical Sciences</option>
                  <option value='Faculty of Veterinary Medicine'>Faculty of Veterinary Medicine</option>
                  <option value='Faculty of Law'>Faculty of Law</option>
                  <option value='Faculty of Life Sciences'>Faculty of Life Sciences</option>
                  <option value='Faculty of Physical Science'>Faculty of Physical Science</option>
                  <option value='Faculty of Environmental Sciences'>Faculty of Environmental Sciences</option>
                  <option value='Teaching Support Unit'>Teaching Support Unit</option>
                  <option value='Pro-Chancellor Office'>Pro-Chancellor Office</option>
                  <option value='Vice-Chancellor Office'>Vice-Chancellor Office</option>
                  <option value='Computer Services and Information Technology'>Computer Services and Information Technology</option>
                  <option value='Registry'>Registry</option>
                  <option value='Bursary'>Bursary</option>
                  <option value='Academic Centres'>Academic Centres</option>
                  <option value='Works and Health Services'>Works and Health Services</option>
                  <option value='Student Services'>Student Services</option>
                  <option value='Unilorin Resources Management Board'>Unilorin Resources Management Board</option>
                  <option value='Other Academic Units'>Other Academic Units</option>
                  <option value='College of Health Sciences'>College of Health Sciences</option>
                  <!--option value=''></option>
                  <option value=''></option>
                  <option value=''></option>
                  <option value=''></option>
                  <option value=''></option>
                  <option value=''></option>
                  <option value=''></option>
                  <option value=''></option-->
			      </select></td>
		      </tr>
              <tr id="trCapitals">
			    <th width="18%" align="left" valign="middle" height="33" nowrap>BUDGET SUB CATEGORY:</th>
			    <td width="82%" align="left" valign="middle" height="33"><input type="text" name="bsubcat2" id="bsubcat2" style="width:300px" value=""></td>
		      </tr>
              <tr>
			    <td align="left" valign="middle" height="33" colspan="2">
			  <strong>ACCOUNT CODE:</strong><br>
		    <tr bgcolor="#6BF4C7">
		      <th height="50" colspan="3" align="center" valign="middle">
              	<script>
		function getSelected(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				//alert('Memo ID:'+row.memo_id+"\nAmount:"+row.amount);
				document.getElementById('tmemoid').value=row.memo_id;
				document.getElementById('memo_unit_code').value=row.dept_unit;
				
				$('#idoc').attr("href", row.memo_id.replace('/', '') );
				$('#idoc').attr("href", $('#idoc').attr("href").replace('/', '') );
				$('#idoc').attr("href", "upload_files/" + $('#idoc').attr("href").replace('/', '') + ".pdf" );
				
				document.getElementById('hmemoid').innerHTML=row.memo_id;
				document.getElementById('hmemofrom').innerHTML=row.memo_from;
				document.getElementById('haddress_unit').innerHTML=row.address_unit;
				
				document.getElementById('vmemoid').innerHTML=row.memo_id;
				document.getElementById('vmemoaction').innerHTML=row.memo_status;
				document.getElementById('vmemodate').innerHTML=row.datein;
				document.getElementById('hmemoamountd').innerHTML=row.amount;
				
				$("#vmemofrom").attr("value", row.memo_from);
				//$("#vaddress_unit").attr("value", row.address_unit);
				$("#vmemodesc").text(row.description);
				$("#vmemoamount").attr("value", row.amount);
				$("#hmemoamount").attr("value", row.amount);
				$("#vmemodept option:selected").attr("value", row.dept_unit);
				$("#vmemodept option:selected").text(row.dept_unit);
				$("#vaddress_unit option:selected").attr("value", row.address_unit);
				$("#vaddress_unit option:selected").text(row.address_unit);
				$("#vmemoid_x").attr("value", row.memo_id);
				
				$('#vidoc').attr("href", row.memo_id.replace('/', '') );
				$('#vidoc').attr("href", $('#vidoc').attr("href").replace('/', '') );
				$('#vidoc').attr("href", "upload_files/" + $('#vidoc').attr("href").replace('/', '') + ".pdf" );
			}
		}
		
		function getSelected_treated(){
			var row = $('#dgout').datagrid('getSelected');
			 getSelections();
			if (row){
				//alert(row.folio_code);
				swapcontent('folio_code_breakdown_journal', row.folio_code);
			}
		}
		function reload_grids(){
			$('#dgout').datagrid('reload');
			$('#dgstaff').datagrid('reload');
		}

		function getSelections(){
			var ids = [];
			var rows = $('#dgout').datagrid('getSelections');
			//alert(rows);
			for(var i=0; i<rows.length; i++){
				ids.push(rows[i].folio_code);
			}
			//alert(rows.ids);
			swapcontent('folio_code_breakdown_journal', ids);
			//alert(ids.join('\n'));
			//document.getElementById('tmemoid').value=ids.join('\n');
		}
	</script>

          <table id="dgout" title="" style="width:auto;" data-options="
                singleSelect:true,
                url: 'scriptfile_m.php?contentvar=foliocode_grid',
                rownumbers:true,method:'get',toolbar:'#tb_2g', pagination:true,
                pageSize:10">
        <thead>
            <tr>
                <th data-options="field:'ck',checkbox:true"></th>
                <!--th data-options="field:'category',width:120"><strong>CATEGORY</strong></th-->
                <th data-options="field:'folio_code',width:100"><strong>ITEM CODE</strong></th>
                <th data-options="field:'title',width:250"><strong>DESCRIPTION</strong></th>
                <th data-options="field:'ncoa_code',width:80"><strong>NCOA CODE</strong></th>
                <th data-options="field:'ncoa_title',width:180,align:'left'"><strong>NCOA TITLE</strong></th>
                <th data-options="field:'exp',width:80,align:'left'"><strong>CODE CATEGORY</strong></th>
            </tr>
        </thead>
    </table>
            
            <script>
		//script for pagination starts
        (function($){
            function pagerFilter(data){
                if ($.isArray(data)){    // is array
                    data = {
                        total: data.length,
                        rows: data
                    }
                }
                var dg = $(this);
                var state = dg.data('datagrid');
                var opts = dg.datagrid('options');
                if (!state.allRows){
                    state.allRows = (data.rows);
                }
                var start = (opts.pageNumber-1)*parseInt(opts.pageSize);
                var end = start + parseInt(opts.pageSize);
                data.rows = $.extend(true,[],state.allRows.slice(start, end));
                return data;
            }
 
            var loadDataMethod = $.fn.datagrid.methods.loadData;
            $.extend($.fn.datagrid.methods, {
                clientPaging: function(jq){
                    return jq.each(function(){
                        var dg = $(this);
                        var state = dg.data('datagrid');
                        var opts = state.options;
                        opts.loadFilter = pagerFilter;
                        var onBeforeLoad = opts.onBeforeLoad;
                        opts.onBeforeLoad = function(param){
                            state.allRows = null;
                            return onBeforeLoad.call(this, param);
                        }
                        dg.datagrid('getPager').pagination({
                            onSelectPage:function(pageNum, pageSize){
                                opts.pageNumber = pageNum;
                                opts.pageSize = pageSize;
                                $(this).pagination('refresh',{
                                    pageNumber:pageNum,
                                    pageSize:pageSize
                                });
                                dg.datagrid('loadData',state.allRows);

                            }
                        });
                        $(this).datagrid('loadData', state.data);
                        if (opts.url){
                            $(this).datagrid('reload');
                        }
                    });
                },
                loadData: function(jq, data){
                    jq.each(function(){
                        $(this).data('datagrid').allRows = null;
                    });
                    return loadDataMethod.call($.fn.datagrid.methods, jq, data);
                },
                getAllRows: function(jq){
                    return jq.data('datagrid').allRows;
                }
            })
        })(jQuery);
 
        function getData(){
            var rows = [];
            for(var i=1; i<=800; i++){
                var amount = Math.floor(Math.random()*1000);
                var price = Math.floor(Math.random()*1000);
                rows.push({
                    inv: 'Inv No '+i,
                    date: $.fn.datebox.defaults.formatter(new Date()),
                    name: 'Name '+i,
                    amount: amount,
                    price: price,
                    cost: amount*price,
                    note: 'Note '+i
                });
            }
            return rows;
        }
        
		//script for pagination ends
</script>
    
    <script type="text/javascript">
	//FILTER/SEARCH SCRIPT STAR5T HERE FOR GRIDS
        $(function(){
            var dg = $('#dgout').datagrid();
			
			
            dg.datagrid('enableFilter', [
			
			{
                field:'amount',
                type:'numberbox',
                options:{precision:2},
                op:['equal','notequal','less','greater']
            },
			/*{
                field:'unitcost',
                type:'numberbox',
                options:{precision:1},
                op:['equal','notequal','less','greater']
            },*/
			{
                field:'exp',
                type:'combobox',
                options:{
                    panelHeight:'auto',
                    data:[{value:'',text:'All'},{value:'Assets',text:'Assets'},
						{value:'Expenses',text:'Expenses'},{value:'Income',text:'Income'},{value:'Liabilities',text:'Liabilities'}],
                    onChange:function(value){
                        if (value == ''){
                            dg.datagrid('removeFilterRule', 'exp');
                        } else {
                            dg.datagrid('addFilterRule', {
                                field: 'exp',
                                op: 'equal',
                                value: value
                            });
                        }
                        dg.datagrid('doFilter');
                    }
                }
            }]);
        });
    </script>
            </th>
	        </tr>
            
</td>
		      </tr>
			  <!--tr id="fund_tr">
			    <th align="left" valign="middle" height="33">FUND SOURCE:</th>
			    <td align="left" valign="middle" height="33"><select name="fundsource" id="fundsource" style="width:300px" onChange=" swapcontent('budget_folio');">
			      <option selected="selected" value="">Select item...</option>
			      <?php
						  //$res_c=@mysqli_query($con, "select * from account_funds order by fund_code");
						  $res_c=@mysqli_query($con, "select * from account_funds order by fund_code");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $dept_code=@$rs_c['fund_code']; //$dept_code=@$rs_c['dept_code'];
							  $dept_name=@$rs_c['fund_name']; //$dept_name=@$rs_c['dept_name'];
                              echo "<option value='$dept_code'>$dept_code - $dept_name</option>";
                           }
						 ?>
			      </select></td>
			    </tr-->
			  <tr>
			    <th align="left" valign="middle" height="33" nowrap>DEPARTMENT CODE:</th>
			    <td align="left" valign="middle" height="33"><select name="deptcode" id="deptcode" onchange="swapcontent('budget_folio');" style="width:300px">
			      <option selected="selected" value="">Select item...</option>
			      <?php
                          //$res_c=@mysqli_query($con, "select * from departmenttb order by dept_code");
						  $res_c=@mysqli_query($con, "select * from account_departments order by department_name");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $dept_code=@$rs_c['department_code']; //$dept_code=@$rs_c['dept_code'];
							  $dept_name=@$rs_c['department_name']; //$dept_name=@$rs_c['dept_name'];
                              echo "<option value='$dept_code'>$dept_code - $dept_name</option>";
                           }
				?>
		        </select>
			      <input type="hidden" name="row_id" id="row_id" class="txt" />
			      <input type="hidden" name="budgetcode" id="budgetcode" value="" />
			      <input type="hidden" name="bcat" id="bcat" value='Recurrent' /></td>
		      </tr>
			  <!--tr id="exp_tr">
			    <th align="left" valign="middle" height="33">EXPENSE CODE:</th>
			    <td align="left" valign="middle" height="33"><select name="itemcode" id="itemcode" onchange="swapcontent('budget_folio');" style="width:300px">
			      <option selected="selected" value="">Select item...</option>
			      <?php
                          //$res_c=@mysqli_query($con, "select * from departmenttb order by dept_name");
						  $res_c=@mysqli_query($con, "select distinct itemcode from foliotb order by itemcode");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $dept_code=@$rs_c['itemcode']; //$dept_code=@$rs_c['dept_code'];
							  //$dept_name=@$rs_c['department_name']; //$dept_name=@$rs_c['dept_name'];
                              echo "<option value='$dept_code'>$dept_code</option>";
                           }
				?>
		        </select>
				</td>
		      </tr-->
              <tr>
        				    <th width="18%" height="33" align="left" valign="middle">BUDGET YEAR: </th>
        				    <th align="left" valign="middle"><select name="b_year" id="b_year" onChange="" style="width:300px" class="txt">
        				      <option selected="selected" value="">Select item...</option>
        				      <?php 
				  $dSess = @date('Y');
				for ($t= 2018; $t<=$dSess; $t++)
				{
					$tSession = "$t"; // . "/" . "$t";
					//if ($tSession == $postResultSession) { $tChked = "selected='$selected'"; } else { $tChked = ""; }
					echo "<option value='$tSession'>$tSession</option>";
				}
				?>
      				      </select></th>
			      </tr>
            <tr>
                <th height="33" align="left" valign="middle">AMOUNT: <br /></th>
                <th width="82%" align="left" valign="middle"><input type="text" name="amount" id="amount" style="width:300px" class="txt easyui-textbox" />
                  <input type="hidden" name="row_id3" id="row_id3" class="txt" />
                  <br /></th>
   			</tr>
            <tr>
                <th height="33" align="left" valign="middle">TITLE: <br /></th>
                <th width="82%" align="left" valign="middle"><input type="text" name="budget_title" id="budget_title" style="width:300px" class="txt easyui-textbox" /></th>
   			</tr>
	    </table>
			<br>
			<input type="button" class="btn" onclick="swapcontent('budget_section', 'search');" value=" SEARCH "> | 
			<input type="button" class="btn" onclick="swapcontent('budget_section', 'save');" value=" SAVE BUDGET ">
				   <!--div id="budget_folio"></div-->
</form>
        <p>&nbsp;</p>
        <hr><p>&nbsp;</p>
        <div id="display"> </div>
        <div id="roll"> </div>
        <div id="budget_section"> </div>
             </div><!-- end of <div style="padding:10px 60px 20px 60px">-->  
          </div><!-- end of <div class="easyui-panel" title="New Topic" style="width:400px">-->
            </div><!-- end of content box -->

        </div> <!-- end of content tooplate_content-->
    
    </div> <!-- end of content tooplate_main-->
	
    <div class="cleaner"></div>    
</div> <!-- end of wrapper tooplate_wrapper-->

<div id="tooplate_footer_wrapper">
	<?php include_once("footer.php"); ?>
</div><!-- end of footer  tooplate_footer_wrapper-->

</body>
</html>

<script>
function calculateDeducation(dedRate, principal, type, VAT){
	//var dvat=$('#dvat').val()*1;
  	//var dstamp=$('#dstamp').val()*1;
	var amt=$('#vamount').val()*1;
	var val_calc=0;	var totalDeduction=0;
	//val_calc=(amt/100) * dstamp;
	
	if(dedRate > 0 && principal > 0){
		if(type == true){
			//VAT inclusive
			val_calc=(dedRate/(VAT + 100)) * principal;
		}
		else if(type == false){
			//VAT exclusive
			val_calc=(dedRate/100) * principal;
		}
		
		$('#dstamp_val').html(val_calc.toFixed(2));	
		total_stamp = (amt - (($('#dendowment_val').html() * 1) + ($('#dtax_val').html() * 1) + ($('#dvat_val').html() * 1) + ($('#dstamp_val').html() * 1)));
		$('#total_deduction').html(total_stamp.toFixed(2));
}else{
	total_ded = ($('#total_deduction').html() *1) + ($('#dstamp_val').html() * 1);
	$('#dstamp_val').html(0);			
	$('#total_deduction').html(total_stamp.toFixed(2));
}
}

function vatInclusive(){
	if(dedRate * 1 >= 0){			
		var dvat=$('#dvat').val()*1;
		var amt=$('#vamount').val()*1;
		var val_calc=0;	var total_ded=0;

		var dtax=$('#dtax').val()*1;
		var wht_calc=0;
		
		var dend=$('#dendowment').val()*1;
		var end_calc=0;

		var dstamp=$('#dstamp').val()*1;
		var stamp_calc=0;


            	if($(this).prop('checked') == true){
                	//compute VAT
            		val_calc=(dvat/(dvat + 100))*amt;
                    //compute WHT
                    wht_calc=((dtax/(dvat + 100))*amt);
                    //compute endowment
                    end_calc=(dend/(dvat + 100))*amt;
                    //compute Stamp Duty
                    stamp_calc=(dstamp/(dvat + 100))*amt;
            	}else if($(this).prop('checked') == false){
                	//compute VAT
            		val_calc=(dvat/100)*amt;
                    //compute WHT
                    wht_calc=(dtax/100)*amt;
                    //compute endowment
                    end_calc=(dend/100)*amt;
                    //compute Stamp Duty
                    stamp_calc=(dstamp/100)*amt;
            	}
                //VAT output
                $('#dvat_val').html(val_calc.toFixed(2));
                //WHT output
                $('#dtax_val').html(wht_calc.toFixed(2));
                //ENDOWMENT output
                $('#dendowment_val').html(end_calc.toFixed(2));	
                //STAMP DUTY output
                $('#dstamp_val').html(stamp_calc.toFixed(2));	
                
                total_ded = (amt - (($('#dvat_val').html() * 1) + ($('#dtax_val').html() * 1) + ($('#dendowment_val').html() * 1) + ($('#dstamp_val').html() * 1)));
                $('#total_deduction').html(total_ded.toFixed(2));
		}
}

/*$(document).ready(function(e) {
	$('#dvat_inc').click(function(e){
		alert(124233);
		if(($('#dvat').val() * 1) > 0){
            if($(this).prop("checked") == true){
				alert(1234);
				var dvat=$('#dvat').val();
				var amt=$('#vamount').val();
				var val_calc=0;
				val_calc=(amt/100)*dvat;
                $('#dvat_val').html(val_calc);
            }
            else if($(this).prop("checked") == false){
                $('#dvat_val').html('');
            }
		}
	});
});*/
</script>