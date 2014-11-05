<script src="<?=base_url()?>assets/js/chartFilters.js"></script>

<div class="chartFiltersContainer" data-base-url=<?=base_url()?>>
	<div class="row chartFilters">
		<div class="col-sm-4 text-center">
			<select class="chartFiltersLandlordGroup" data-placeholder="Landlord Group" multiple>
				<?php foreach($filterData['landlordgroups'] as $landlordgroupid => $landlordgroupname): ?>
				<option value="<?=$landlordgroupid?>"><?=$landlordgroupname?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="col-sm-4 text-center">
			<select class="chartFiltersLandlord" data-placeholder="Landlord" multiple>
				<?php foreach($filterData['landlords'] as $landlordid => $landlordname): ?>
				<option value="<?=$landlordid?>"><?=$landlordname?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="col-sm-2 text-center">
			<select class="chartFiltersStartYear" data-placeholder="Start Year">
				<option></option>
				<?php $year = $filterData['yearRange']['MinDate']; while($year <= $filterData['yearRange']['MaxDate']): ?>
				<option value="<?=$year?>"><?=$year?></option>
				<?php $year++; endwhile; ?>
			</select>
		</div>
		<div class="col-sm-2 text-center">
			<select class="chartFiltersEndYear" data-placeholder="End Year">
				<option></option>
				<?php $year = $filterData['yearRange']['MinDate']; while($year <= $filterData['yearRange']['MaxDate']): ?>
				<option value="<?=$year?>"><?=$year?></option>
				<?php $year++; endwhile; ?>
			</select>
		</div>
	</div>
	<div class="row chartFilters whiteBorderBottom">
		<div class="col-sm-12 text-center">
			<button class="chartFiltersSubmit btn btn-default btn-xs btn-block">Filter <i class="fa fa-search"></i></button>
			<br/>
		</div>
	</div>
	<div class="row chartFiltersTab">
		<div class="col-xs-12 text-center">
			<i class="fa fa-bars chartFiltersToggle"></i>
		</div>
	</div>
</div>