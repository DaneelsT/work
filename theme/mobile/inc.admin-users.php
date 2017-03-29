<div class="container" role="main">
	<div class="row">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title"><?php echo translate("Users Overview"); ?></h3>
			</div>
			<div class="panel-body">
				<table class="table">
					<thead>
						<tr>
							<th>ID</th>
							<th><?php echo translate("Username"); ?></th>
							<th><?php echo translate("E-mail"); ?></th>
							<th><?php echo translate("Name"); ?></th>
							<th><?php echo translate("Surname"); ?></th>
							<th><?php echo translate("Surname"); ?></th>
							<th><?php echo translate("Actions"); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
							$users = $this->getUsers();
							$html = "";
							foreach($users as $user) {
							$html .= "<tr>";
							$html .= "<td>" . $user->getId() . "</td>";
							$html .= "<td>" . $user->getUsername() . "</td>";
							$html .= "<td>" . $user->getEmail() . "</td>";
							$html .= "<td>" . $user->getName() . "</td>";
							$html .= "<td>" . $user->getSurname() . "</td>";
							$html .= "<td>" . $user->getGenderString() . "</td>";
							$html .= "<td><a href=" . getBase() . "/user/edit/" . $user->getId() . " class='btn btn-warning'>" . translate("Edit") . "</a></td>";
							}
							echo $html;
						?>
					</tbody>
				</table>
				<a href="<?php placeHttpRoot(); ?>user/add" class="btn btn-default"><?php echo translate("Add a user"); ?></a>
			</div>
		</div>
	</div>
</div>
