<p><br /></p>
<table>
	<tr>
		<td width="200">Accessing from Mobile:</td><td><?php echo is_mobile() ? "Yes" : "No" ?></td>
	</tr>
	<tr>
		<td>Search Engine BOT:</td><td><?php echo is_bot() ? "Yes" : "No" ?></td>
	</tr>
	<tr>
		<td>Ajax Request:</td><td><?php echo ajax ? "Yes" : "No" ?></td>
	</tr>
	<tr>
		<td>Running on Windows:</td><td><?php echo windows ? "Yes" : "No" ?></td>
	</tr>
	<tr>
		<td>Request Type:</td><td><?php echo post ? "POST" : "GET" ?></td>
	</tr>
	<tr>
		<td>Site Root:</td><td><?php echo rootfisico ?></td>
	</tr>
	<tr>
		<td>Site Virtual Root:</td><td><?php echo rootvirtual ?></td>
	</tr>
	<tr>
		<td>Lang:</td><td><?php echo request_lang ?></td>
	</tr>
	<tr>
		<td>Available Languages:</td><td><?php echo av_lang ?></td>
	</tr>
</table>
