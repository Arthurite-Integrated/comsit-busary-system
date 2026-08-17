<div title="<span class='tt-inner'>Research</span>" style="padding:10px">
<fieldset>
<legend>
<font color="red">
     <b>Use this form to provide research history. Note that this section is meant for academic staff only. Cross-check your entry before submission.</b>
</font>
</legend>

<table width="80%" border="0">
<tr>
     <td width="20%">Research Topic:</td>
     <td width="28%">
          <input class="form-control" name="res_topic" type="text" id="res_topic" />
     </td>
     <td width="12%" nowrap="nowrap">Research Status:</td>
     <td width="40%">
          <select name="res_status" id="res_status" class="form-control">
               <option selected="selected" value="">---Select Option---</option>
               <option value="In Progress">In Progress</option>
               <option value="Completed">Completed</option>
          </select>
     </td>
</tr>
<div class="col-sm-6 x">
     <label class="col-form-label">Funding Source:</label>
     <div class="">
          <input class="form-control" name="res_funding" type="text" id="res_funding" />
     </td>
</tr>
<tr>
     <td nowrap="nowrap">Amount Granted:</label>
          <div class="">
               <input class="form-control" name="res_amount" type="text" id="res_amount" />
          </label>
          <div class="">Value of Project:</label>
               <div class="">
                    <input class="form-control" name="res_value" type="text" id="res_value" />
               </td>
          </tr>
          <div class="col-sm-6 x">
               <label class="col-form-label">Start Date:</label>
               <div class="">
                    <input class="form-control" name="res_start_date" type="text" id="res_start_date" />
               </td>
               <td nowrap="nowrap">End Date:</label>
                    <div class="">
                         <input class="form-control" name="res_end_date" type="text" id="res_end_date" />
                    </td>
               </tr>
               <tr>
                    <td colspan="4">
                         <div align="center">
                              <input type="button" name="cmdemp" id="cmdemp" value="Submit" class="btn btn-outline-primary btn-fw" onClick="swapcontent('add_research','save');"/>
                         </div>
                    </td>
               </tr>
               <tr>
                    <td colspan="4">
                         <div id="add_research">

                         </div>
                    </td>
               </tr>
          </table>

     </fieldset>
</div>
