<main>
	<div class="panel panelSeperatorBottom">
		<div class="panelContent">
			<h2><?php echo translate("Users Overview"); ?> <a href="<?php placeHttpRoot(); ?>user/add" class="button right"><?php echo translate("Add a user"); ?></a></h2>
			
			<?php
			$users = $this->getUsers();
			$html = "";
			?>
			<table class="width100">
				<tr>
					<th>ID</th>
					<th><?php echo translate("Username"); ?></th>
					<th><?php echo translate("E-mail"); ?></th>
					<th><?php echo translate("Name"); ?></th>
					<th><?php echo translate("Surname"); ?></th>
					<th><?php echo translate("Surname"); ?></th>
					<th><?php echo translate("Actions"); ?></th>
				</tr>
			<?php
			foreach($users as $user) {
				$html .= "<tr>";
				$html .= "<td>" . $user->getId() . "</td>";
				$html .= "<td>" . $user->getUsername() . "</td>";
				$html .= "<td>" . $user->getEmail() . "</td>";
				$html .= "<td>" . $user->getName() . "</td>";
				$html .= "<td>" . $user->getSurname() . "</td>";
				$html .= "<td>" . $user->getGenderString() . "</td>";
				$html .= "<td><a href=" . getBase() . "/user/edit/" . $user->getId() . " class='button'>" . translate("Edit") . "</a></td>";
			}
			echo $html;
			?>
		</div>
	</div>
</main>