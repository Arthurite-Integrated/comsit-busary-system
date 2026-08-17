<div onClick="swapcontent('mailsearch');" title="<span class='tt-inner'><img src='images/searchmail.png'/><br>Search Mail</span>" style="padding:10px">
                                    <table id="dg_group" style="width:650px;height:250px"
                                        url="scriptfile_m.php?contentvar=memo_withsub"
                                        title=""
                                        singleSelect="true" fitColumns="true" pagination="true"
                                        pageSize = "10" toolbar="tb_2s" >
                                        <thead>
                                             <tr>
                                                  <th data-options="field:'ck',checkbox:true"></th>
                                                  <th field="memo_id" width="100">Memo ID</th>
                                                  <th field="memo_from" width="100">From</th>
                                                  <th data-options="field:'address_unit',width:100,hidden:'false'">Address/Unit</th>
                                                  <th field="description" align="left" width="180">Description</th>
                                                  <th field="amount" align="left" width="100">Amount</th>
                                                  <th data-options="field:'dept_unit',width:100,align:'left',hidden:'false'">Dept/Unit</th>
                                                  <th field="datein" width="60">Date</th>
                                                  <th field="memo_status" width="80" align="left">Status</th>
                                             </tr>
                                        </thead>
                                   </table>
                                   <script type="text/javascript">
                                        $(function(){
                                                $('#dg_group').datagrid({
                                                    view: detailview,
                                                    detailFormatter:function(index,row){
                                                        return '<div style="padding:2px"><table class="ddv"></table></div>';
                                                    },
                                                    onExpandRow: function(index,row){
                                                        var ddv = $(this).datagrid('getRowDetail',index).find('table.ddv');
                                                        ddv.datagrid({
                                                            url:'scriptfile_m.php?contentvar=memo_sub&memo_id='+row.memo_id,
                                                            fitColumns:true,
                                                            singleSelect:true,
                                                            rownumbers:true,
                                                            loadMsg:'',
                                                            height:'auto',
                                                            columns:[[
                                                                    {field:'memo_status',title:'STATUS',width:50},
                                                                    {field:'unit_name',title:'DEPT/UNIT',width:180,align:'left'},
                                                                    {field:'date',title:'DATE',width:60,align:'left'},
                                                                    {field:'remark',title:'REMARK',width:150,align:'left'},
                                                                    {field:'action',title:'ACTION',width:80,align:'left'}
                                                            ]],
                                                            onResize:function(){
                                                                    $('#dg_group').datagrid('fixDetailRowHeight',index);
                                                            },
                                                            onLoadSuccess:function(){
                                                                    setTimeout(function(){
                                                                        $('#dg_group').datagrid('fixDetailRowHeight',index);
                                                                    },0);
                                                            }
                                                        });
                                                        $('#dg_group').datagrid('fixDetailRowHeight',index);
                                                    }
                                                });
                                        });
                                   </script>
                                   <script type="text/javascript">
                                        var url;
                                        function open_memo(){
                                                /*alert(1343234);*/
                                                var row = $('#dg').datagrid('getSelected');
                                                /*$('#dlg').dialog('open').dialog('setTitle', "New Window"); exit;*/
                                                if (row){
                                                    $('#dlg').dialog('open').dialog('setTitle', row.memo_id + "::" + row.memo_from);
                                                    $('#fm').form('load',row);
                                                    url = 'scriptfile_m.php?contentvar=memo_meovement&memo_id=1';
                                                }
                                        }
                                   </script>
                                   <div id="dlg" class="easyui-window" title="Basic Window" data-options="iconCls:'icon-tip'" style="width:500px;height:200px;padding:10px;" closed="true">
                                        <form id="fm" method="post" novalidate>
                                             <table id="dgx" title="" style="width:680px;" data-options="
                                             singleSelect:true,
                                             url: 'scriptfile_m.php?contentvar=xxx',
                                             rownumbers:true,method:'get'
                                             ">
                                             <thead>
                                                  <tr>
                                                       <th data-options="field:'memo_id',width:100">ID</th>
                                                       <th data-options="field:'memo_from',width:100">FROM</th>
                                                       <th data-options="field:'description',width:180,align:'left'">DESCRIPTION</th>
                                                       <th data-options="field:'amount',width:100,align:'left'">AMOUNT</th>
                                                       <th data-options="field:'datein',width:60">DATE</th>
                                                       <th data-options="field:'memo_status',width:80,align:'center'">STATUS</th>
                                                  </tr>
                                             </thead>
                                        </table>
                                   </form>
                              </div>
                              <!--<div id="mailsearch"></div>-->

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

                              $(function(){
                                   ////$('#dg').datagrid({data:getData()}).datagrid('clientPaging');
                              });
                              //script for pagination ends
                    </script>

                        <script type="text/javascript">
                              var toolbar = [{
                                   text:'TRACK MAIL',
                                   iconCls:'icon-tip',
                                   handler:function(){
                                        //alert('PUT MEMO MOVEMENT HERE!')
                                        getSelected()
                                   }
                              }/*,{
                                   text:'Cut',
                                   iconCls:'icon-cut',
                                   handler:function(){alert('cut')}
                              },'-',{
                              text:'Save',
                              iconCls:'icon-save',
                              handler:function(){alert('save')}
                         }*/];
                    </script>



                    <script type="text/javascript">
                         //FILTER/SEARCH SCRIPT STAR5T HERE FOR GRIDS
                         $(function(){
                              var dg = $('#dg_group').datagrid();


                              dg.datagrid('enableFilter', [{
                                   field:'amount',
                                   type:'numberbox',
                                   options:{precision:2},
                                   op:['equal','notequal','less','greater']
                              },/*{
                                   field:'unitcost',
                                   type:'numberbox',
                                   options:{precision:1},
                                   op:['equal','notequal','less','greater']
                              },*/{
                              field:'memo_status',
                              type:'combobox',
                              options:{
                                   panelHeight:'auto',
                                   data:[{value:'',text:'All'},{value:'In Progress',text:'In Progress'},
                                   {value:'Queried',text:'Queried'},{value:'Completed',text:'Completed'}],
                                   onChange:function(value){
                                        if (value == ''){
                                             dg.datagrid('removeFilterRule', 'memo_status');
                                        } else {
                                             dg.datagrid('addFilterRule', {
                                                  field: 'memo_status',
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

                    <script type="text/javascript">
                    //FILTER/SEARCH SCRIPT STAR5T HERE FOR GRIDS
                    $(function(){
                         var dg = $('#dgout').datagrid();


                         dg.datagrid('enableFilter', [{
                              field:'amount',
                              type:'numberbox',
                              options:{precision:2},
                              op:['equal','notequal','less','greater']
                         },/*{
                              field:'unitcost',
                              type:'numberbox',
                              options:{precision:1},
                              op:['equal','notequal','less','greater']
                         },*/{
                         field:'memo_status',
                         type:'combobox',
                         options:{
                              panelHeight:'auto',
                              data:[{value:'',text:'All'},{value:'In Progress',text:'In Progress'},
                              {value:'Queried',text:'Queried'},{value:'Completed',text:'Completed'}],
                              onChange:function(value){
                                   if (value == ''){
                                        dg.datagrid('removeFilterRule', 'memo_status');
                                   } else {
                                        dg.datagrid('addFilterRule', {
                                             field: 'memo_status',
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

               <script type="text/javascript">
               //FILTER/SEARCH SCRIPT STAR5T HERE FOR GRIDS
               $(function(){
                    var dg = $('#dg').datagrid();


                    dg.datagrid('enableFilter', [{
                         field:'amount',
                         type:'numberbox',
                         options:{precision:2},
                         op:['equal','notequal','less','greater']
                    },/*{
                         field:'unitcost',
                         type:'numberbox',
                         options:{precision:1},
                         op:['equal','notequal','less','greater']
                    },*/{
                    field:'memo_status',
                    type:'combobox',
                    options:{
                         panelHeight:'auto',
                         data:[{value:'',text:'All'},{value:'In Progress',text:'In Progress'},
                         {value:'Queried',text:'Queried'},{value:'Completed',text:'Completed'}],
                         onChange:function(value){
                              if (value == ''){
                                   dg.datagrid('removeFilterRule', 'memo_status');
                              } else {
                                   dg.datagrid('addFilterRule', {
                                        field: 'memo_status',
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

     </div>