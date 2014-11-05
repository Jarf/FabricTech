<html>
	<head>
		<!-- Metadata -->
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- Load CSS and JS -->
		<script src="/assets/js/jquery-1.11.1.min.js"></script>
		<script src="/assets/js/jquery-ui.min.js"></script>
		<script src="/assets/js/bootstrap.min.js"></script>
		<script src="/assets/js/Chart.min.js"></script>
		<script src="/assets/js/chosen.jquery.min.js"></script>
		<script src="/assets/js/chartHeader.js"></script>
		<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="/assets/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="/assets/css/chosen.min.css">
		<link rel="stylesheet" type="text/css" href="/assets/css/application.css">
		<link rel="stylesheet" type="text/css" href="/assets/css/jquery-ui.min.css">

		<title>Fabric Tech Assessment</title>
	</head>
	<body>
		<div class="container container-main chartHeaderContainer">
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
