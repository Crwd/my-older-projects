<?php
// $quoteControl defined in welcome.php (Quotes class)
$data = $quoteControl->getQuotes();

if(!empty($data)) {
	foreach($data as $quote) {
		$date = $quoteControl->getDate($quote['time']);
		
		echo('<div class="quoteBox col col-md-12">
			<div class="row quoteHeader">
				<span class="col col-md-12">
					<span class="headline"><b>' . $quote['author'] . '</b> &middot; ' . $date . '</span>
				</span>
			</div>

			<div class="row quoteContent">
				<p class="col col-md-12">' . $quote['content'] . '</p>
			</div>

			<div class="row quoteStats">
				<p class="col col-md-12"><span class="likeIcon glyphicon glyphicon-thumbs-up"></span> <span class="likeCount">' . $quote['likes'] . '</span></p>
			</div>
		</div>');
	}

	echo '<div style="display:none" loadID="show">' . $data[0]["ID"] . '</div>';
}