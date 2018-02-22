<div class="article">
	<h2>Zoznam článkov</h2>
	<table id="table-home">
		<tr>
			<th width="300px"><strong>Názov článku</strong></th>
			<th width="200px"><strong>Rubrika</strong></th>
			<th width="70px"><strong>Autor</strong></th>
			<th width="100px"><strong>Publikovanie</strong></th>
		<?php if (strcmp($this->privileges, "admin") === 0 ) {?>
			<th width="70px"><strong>Zmena</strong></th>
			<th width="70px"><strong>Status</strong></th>
		<?php }?>
		</tr>
	</table>
</div>
<!-- [Zaciatok] Bocne menu -->
<div id="menu">
	<h3>Menu</h3>
</div>
<!-- [Koniec] Bocne menu -->
