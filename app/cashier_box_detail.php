<?php
		$sql=$select->list_cashier_box_detail($ref);
		$jmldata = $sql->rowCount();
 ?>
 
 <table class="table table-bordered table-condensed table-hover table-striped">
	<thead>
		<tr style="color: #168124; font-weight: bold; border: 1px solid #ccc; background-color: #e6ffea"> 
			<th><?php if($lng==1) { echo 'Item Name'; } else { echo 'Nama Barang'; } ?></th> 
			<th>Unit</th> 									 
			<th><?php if($lng==1) { echo 'Qty'; } else { echo 'Jml Barang'; } ?></th>	
			<th><?php if($lng==1) { echo 'Unit Price'; } else { echo 'Harga Satuan'; } ?></th>
			<th><?php if($lng==1) { echo 'Discount'; } else { echo 'Potongan'; } ?></th>
            <th><?php if($lng==1) { echo 'Discount (%)'; } else { echo 'Potongan (%)'; } ?></th>
			<th><?php if($lng==1) { echo 'Amount'; } else { echo 'Total'; } ?></th>
			<th><?php if($lng==1) { echo 'Sub Detail'; } else { echo 'Detail Kotak'; } ?></th>
			<th><?php if($lng==1) { echo 'Delete'; } else { echo 'Hapus'; } ?></th>
		</tr>
	</thead>
	<tbody>
		<?php 
		
			$totalx = 0;
			$total2 = 0;
			$no = 0;
			while($row_cashier_detail=$sql->fetch(PDO::FETCH_OBJ)) { 
			
				$qty = number_format($row_cashier_detail->qty, 0, '.', ',');
				$unit_price = number_format($row_cashier_detail->unit_price, 0, '.', ',');
				$discount_det = number_format($row_cashier_detail->discount, 0, '.', ',');
                $discount3_det = number_format($row_cashier_detail->discount3, 2, '.', ',');
				$amount = number_format($row_cashier_detail->amount, 0, '.', ',');
				
				$totalx = $totalx + $row_cashier_detail->amount;
				$total2 = number_format($totalx, 0, '.', ',');
				
				$note = $row_cashier_detail->note;
				
		?>								
			<input type="hidden" id="jmldata" name="jmldata" value="<?php echo $jmldata; ?>" >
			
			<input type="hidden" id="old_item_code_<?php echo $no ?>" name="old_item_code_<?php echo $no ?>" value="<?php echo $row_cashier_detail->item_code; ?>" >
			<input type="hidden" id="old_uom_code_<?php echo $no ?>" name="old_uom_code_<?php echo $no ?>" value="<?php echo $row_cashier_detail->uom_code; ?>" >
			<input type="hidden" id="old_line_<?php echo $no ?>" name="old_line_<?php echo $no ?>" value="<?php echo $row_cashier_detail->line; ?>" >
		
			<input type="hidden" id="item_code_<?php echo $no ?>" name="item_code_<?php echo $no ?>" value="<?php echo $row_cashier_detail->item_code; ?>" >	
			
			<input type="hidden" id="old_qty_<?php echo $no; ?>" name="old_qty_<?php echo $no; ?>" style="text-align: right; width: 70px" class="form-control" value="<?php echo $qty ?>" >
			
			<tr style="background-color:ffffff;" id="item_ajax2_<?php echo $no; ?>" > 
				
				<td>				
					<?php echo $row_cashier_detail->item_name; ?>	
					
				</td>
				<td>
					<input type="text" id="uom_code_<?php echo $no ?>" name="uom_code_<?php echo $no ?>" class="form-control" readonly="" style="width: 50px" value="<?php echo $row_cashier_detail->uom_code; ?>" >		
				</td>
				<td align="center">
					<input type="text" id="qty_<?php echo $no; ?>" name="qty_<?php echo $no; ?>" style="text-align: right; font-size:12px " class="form-control" onkeyup="formatangka('qty_<?php echo $no; ?>'), detailvalue('<?php echo $no ?>', '<?php echo $jmldata ?>')" value="<?php echo $qty ?>" >
				</td>
				<td align="center">
					<input type="text" id="unit_price_<?php echo $no; ?>" name="unit_price_<?php echo $no; ?>" style="text-align: right; font-size:12px" class="form-control" onkeyup="formatangka('unit_price_<?php echo $no; ?>'), detailvalue('<?php echo $no ?>', '<?php echo $jmldata ?>')" value="<?php echo $unit_price ?>" >
				</td>
				<td align="center" id="discount_det_id<?php echo $no; ?>">
					<input type="text" id="discount_<?php echo $no; ?>" name="discount_<?php echo $no; ?>" style="text-align: right; font-size:12px" class="form-control" onkeyup="formatangka('discount_<?php echo $no; ?>'), detailvalue('<?php echo $no ?>', '<?php echo $jmldata ?>', 'nominal')" value="<?php echo $discount_det ?>" >
				</td>
                <td align="center" id="discount3_det_id<?php echo $no; ?>">
					<input type="text" id="discount3_<?php echo $no; ?>" name="discount3_<?php echo $no; ?>" style="text-align: right; font-size:12px" class="form-control" onkeyup="formatangka('discount3_<?php echo $no; ?>'), detailvalue('<?php echo $no ?>', '<?php echo $jmldata ?>', 'persen')" value="<?php echo $discount3_det ?>" >
				</td>
				<td align="center" id="amount<?php echo $no; ?>">
					<input type="text" id="amount_<?php echo $no; ?>" name="amount_<?php echo $no; ?>" style="text-align: right; font-size:12px" class="form-control" onkeyup="formatangka('amount_<?php echo $no; ?>')" readonly value="<?php echo $amount ?>" >
				</td>
				<!--<td align="center">
					<input type="text" id="note_<?php echo $no; ?>" name="note_<?php echo $no; ?>" style="text-align: left; font-size:12px" class="form-control" value="<?php echo $note ?>" >
				</td>-->
				<td align="center">
					<a href="javascript:void(0);" name="Find" title="Detail Kotak" onClick="subdetail_item('<?php echo $ref ?>','<?php echo $row_cashier_detail->line ?>')"><img src="assets/img/plus.png" /></a>
				</td>
				<td align="center">
					<input type="checkbox" id="delete_<?php echo $no; ?>" name="delete_<?php echo $no; ?>" class="ace" value="1" ><span class="lbl"></span>
					
				</td>				
			</tr>
			
			<?php
				$sql_det=$select->list_cashier_box_subdetail($ref, $row_cashier_detail->line);
				$jmldata_det = $sql_det->rowCount();
				
				if($jmldata_det > 0) {
			?>
					<tr>
						<td></td>
						<td colspan="6">
							<table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top" style="font-size: 11px">
								<tr>
									<td>No</td>
									<td>Nama</td>
									<td>Satuan</td>
									<td>Qty</td>
									<td>Harga</td>
									<td>Jumlah</td>
								</tr>
								<?php
									$nomor = 0;
									while($row_detail=$sql_det->fetch(PDO::FETCH_OBJ)) { 
									
									$nomor++;
								?>
									<tr>
										<td><?php echo $nomor ?></td>
										<td><?php echo $row_detail->item_name ?></td>
										<td><?php echo $row_detail->uom_code ?></td>
										<td align="center"><?php echo $row_detail->qty ?></td>
										<td align="right"><?php echo number_format($row_detail->unit_price,0,'.',',') ?></td>
										<td align="right"><?php echo number_format($row_detail->amount,0,'.',',') ?></td>
									</tr>
								<?php
									}
								?>
							</table>
						</td>
					</tr>
			<?php 
				}
										
				$no++; 
			} 
			
			?>
			
			
			<!----     detail add------------------->
			<tr style="border: 1px solid #cccccc;" id="item_ajax_<?php echo $no; ?>">
	          	<td style="border-right: 1px solid #cccccc;">
	          		
	          		<input type="hidden" id="item_code" name="item_code" class="form-control chzn-select-deselect" value="">
	          		
	          		<select id="item_code3_<?php echo $no ?>" name="item_code3_<?php echo $no ?>" class="chosen-select form-control" onchange="loadHTMLPost3('<?php echo $__folder ?>app/cashier_box_detail_ajax.php','item_ajax_<?php echo $no; ?>','getdata_code2','item_code3_<?php echo $no; ?>','jmldata')" >
						<option value=""></option>
						<?php 
							select_item("")
						?>	
					</select>
					
	          	</td>
	          	
	          	<td style="border-right: 1px solid #cccccc;">
					<select id="uom_code" name="uom_code" class="form-control" style="height: 35px; width: auto;">
						<option value=""></option>
						<?php 
							select_uom("") 
						?>
					</select>	
				</td>
				<td align="left" style="border-right: 1px solid #cccccc;">
					<input type="text" id="qty" name="qty" style="text-align: right;" class="form-control" onkeyup="formatangka('qty'), detailvalue2()" autocomplete="off" value="" >
				</td>
				<td align="right" style="border-right: 1px solid #cccccc;">
					<input type="text" id="unit_price" name="unit_price" style="text-align: right;" class="form-control" onkeyup="formatangka('unit_price'), detailvalue2()" autocomplete="off" value="" >
				</td>
				<td align="center">
					<input type="text" id="discount_det" name="discount_det" style="text-align: right; font-size: 12px; width: 90px" class="form-control" onKeyPress="return focusNext('submit_det',event)" onkeyup="formatangka('discount_det'), detailvalue2('nominal')" autocomplete="off" value="" >
				</td>
		        
		        <td align="center">
					<input type="text" id="discount3_det" name="discount3_det" style="text-align: right; font-size: 12px; width: 90px" class="form-control" onKeyPress="return focusNext('submit_det',event)" onkeyup="formatangka('discount3_det'), detailvalue2('persen')" autocomplete="off" value="" >
				</td>
				<td align="right" style="border-right: 1px solid #cccccc;" id="amount_det">
					<input type="text" id="amount" name="amount" style="text-align: right;" class="form-control" onkeyup="formatangka('amount')" autocomplete="off" readonly="" value="" >
				</td>
	        </tr>
			<!----     end detail add--------------->
			
			<tr>
				<td colspan="4" align="right" style="font-size: 14px; font-weight: bold;">Discount&nbsp;</td>
				<td align="right">
					<input type="text" id="discount" name="discount" style="text-align: right; font-size: 14px; font-weight: bold;" class="form-control" onkeyup="formatangka('discount'), sub_total('<?php echo $jmldata ?>')" value="<?php echo $discount2 ?>" >
				</td>
				<td colspan="1" align="right" style="font-size: 14px; font-weight: bold;">Total&nbsp;</td>
				<td align="right" id="total_id">
					<input type="text" id="total" name="total" readonly="" style="text-align: right; font-size: 14px; font-weight: bold;" class="form-control" onkeyup="formatangka('total')" value="<?php echo $total ?>" >
				</td>
				<td colspan="4">
					&nbsp;
				</td>
			</tr>
			
			<tr>
				<td colspan="4" align="right" style="font-size: 14px; font-weight: bold;"><?php if($lng==1) { echo 'DP (Down Payment)'; } else { echo 'Uang Muka'; } ?>&nbsp;</td>
				<td align="right">
					<input type="text" id="deposit" name="deposit" style="text-align: right; font-size: 14px; font-weight: bold;" class="form-control" onkeyup="formatangka('deposit'), sub_total('<?php echo $jmldata ?>')" value="<?php echo $deposit ?>" >
				</td>
				<td align="right" style="font-size: 14px; font-weight: bold;">Cash&nbsp;</td>
				<td align="right">
					<input type="text" id="cash_amount" name="cash_amount" style="text-align: right; font-size: 14px; font-weight: bold;" class="form-control" autocomplete="off" onkeyup="formatangka('cash_amount'), sub_total('<?php echo $jmldata ?>')" value="<?php echo $cash_amount ?>" >
				</td>
			</tr>
			
			<tr>
				<td align="center" style="color: #ff0000; font-weight: bold; font-size: 14px">
					<?php if ( allowlvl('frmcashier_pos') == 1 || $_SESSION['adm'] == 1 ) { ?>
						Batal : 
						<input type="checkbox" name="void" id="void" class="ace" value="1" <?php echo $void ?> /><span class="lbl"></span>
					<?php } ?>
				</td>
				<td colspan="3" style="color: #ff0000; font-weight: bold; font-size: 14px">
					<?php if ( allowlvl('frmcashier_pos') == 1 || $_SESSION['adm'] == 1 ) { ?>
						Catatan Batal : 
						<input type="text" name="void_memo" id="void_memo" class="form-control" autocomplete="off" value="<?php echo $row_cashier->void_memo ?>" />
					<?php } ?>
				</td>
				<td colspan="1"></td>
				<td align="right" style="font-size: 14px; font-weight: bold;">Debit&nbsp;</td>
				<td align="right">
					<input type="text" id="bank_amount" name="bank_amount1" autocomplete="off" style="text-align: right; font-size: 14px; color: #000000; font-weight: bold;" class="form-control" onkeyup="formatangka('bank_amount')" oninput="sub_total('<?php echo $jmldata ?>')" onKeyPress="return focusNext('submit',event)" value="<?php echo $bank_amount ?>" >
				</td>
			</tr>
			
			<tr>
				<td colspan="5">&nbsp;</td>
				<td align="right" style="font-size: 14px; font-weight: bold;">OVO&nbsp;</td>
				<td align="right">
					<input type="text" id="ovo" name="ovo" style="text-align: right; font-size: 14px; color: #000000; font-weight: bold;" class="form-control" onkeyup="formatangka('ovo'), sub_total('<?php echo $jmldata ?>')" value="<?php echo $ovo ?>" >
				</td>
			</tr>
			
			<tr>
				<td colspan="5"></td>
				<td align="right" style="font-size: 14px; font-weight: bold;">Gopay&nbsp;</td>
				<td align="right">
					<input type="text" id="gopay" name="gopay" autocomplete="off" style="text-align: right; font-size: 14px; color: #000000; font-weight: bold;" class="form-control" onkeyup="formatangka('gopay')" oninput="sub_total('<?php echo $jmldata ?>')" onKeyPress="return focusNext('submit',event)" value="<?php echo $gopay ?>" >
				</td>
			</tr>
			
			<tr>
				<td colspan="5">&nbsp;</td>
				<td align="right" style="font-size: 14px; font-weight: bold;">Voucher&nbsp;</td>
				<td align="right">
					<input type="text" id="cash_voucher" name="cash_voucher" style="text-align: right; font-size: 14px; color: #000000; font-weight: bold;" class="form-control" autocomplete="off" onkeyup="formatangka('cash_voucher'), sub_total('<?php echo $jmldata ?>')" value="<?php echo $cash_voucher ?>" >
				</td>
			</tr>
			
			<?php include("cashier_bank.php"); ?>
			
			<tr>
				<td colspan="6" align="right" style="font-size: 14px; font-weight: bold;">Kembalian&nbsp;</td>
				<td align="right" id="change_amount_id">
					<input type="text" id="change_amount" name="change_amount" readonly="" style="text-align: right; font-size: 14px; font-weight: bold;" class="form-control" onkeyup="formatangka('change_amount')" value="<?php echo number_format($row_cashier->change_amount,0,".",",") ?>" >
				</td>
			</tr>
										
	</tbody>
</table>