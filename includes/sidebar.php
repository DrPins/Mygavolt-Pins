<div class="sidebar">
	<h4>Nouveautés</h4>


	<?php
				$select = $db->prepare("SELECT TOP 2 * FROM products ORDER BY id DESC ");
				$select->execute();

				while($s=$select->fetch(PDO::FETCH_OBJ)){?>
					<a href="?show=<?php echo $s->id;?>"><img src="admin/imgs/<?php echo $s->nom_img?>.jpg" class="img-fluid" alt="Responsive image"></a>
					<a href="?show=<?php echo $s->id;?>"><?php echo $s->label;?></a>
					<?php
				}
				?>
</div>
