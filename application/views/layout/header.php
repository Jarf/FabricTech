<html>
	<head>
		<!-- Metadata -->
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- Load CSS and JS -->
		<script src="<?=base_url()?>assets/js/jquery-1.11.1.min.js"></script>
		<script src="<?=base_url()?>assets/js/jquery-ui.min.js"></script>
		<script src="<?=base_url()?>assets/js/bootstrap.min.js"></script>
		<script src="<?=base_url()?>assets/js/Chart.min.js"></script>
		<script src="<?=base_url()?>assets/js/chosen.jquery.min.js"></script>
		<script src="<?=base_url()?>assets/js/chartHeader.js"></script>
		<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/chosen.min.css">
		<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/application.css">
		<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/jquery-ui.min.css">

		<title>Fabric Tech Assessment</title>
	</head>
	<body>
		<div class="container container-main chartHeaderContainer" data-base-url=<?=base_url()?>>
			<div class="row row-header">
				<div class="col-xs-4 text-left">
					<h4>Costs Chart Viewer</h4>
				</div>
				<div class="col-xs-4 text-center">
					<input type="text" class="chartHeaderSearch form-control" placeholder="Search">
				</div>
				<div class="col-xs-4 text-right">
					<button class="btn btn-danger chartHeaderImport">Import <i class="fa fa-mail-reply"></i></button>
					<button class="btn btn-danger chartHeaderImportLoading" disabled="disabled"><i class="fa fa-spinner fa-spin"></i></button>
					<button class="btn btn-success chartHeaderImportSuccess">
						Success <i class="fa fa-check"></i><br/>
					</button>
				</div>
			</div>
