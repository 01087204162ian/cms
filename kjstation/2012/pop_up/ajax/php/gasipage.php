<?  $max_num = 14;
	if (!$pages) {$pages = 1;}

	$total_page = ceil($total / $max_num);

	// 이전 페이지값 구함 (1보다 작을 경우 1로 지정)
	$prev_page = $pages - 1;
	if ($prev_page < 1) {$prev_page = 1;}

	// 다음 페이지값 구함 (전체페이지값 넘으면 전체페이지값으로 지정)
	$next_page = $pages + 1;
	if ($next_page > $total_page) {$next_page = $total_page;}

	// 페이지 인덱스의 시작과 종료 범위 구함
	if ($pages%$max_num) $start_page = $pages - $pages%$max_num + 1;
	else          $start_page = $pages - ($max_num - 1);
	$end_page = $start_page + $max_num;

	// 이전 페이지 그룹을 지정
	$prev_group = $start_page - 1;
	if ($prev_group < 1) $prev_group = 1;

	// 다음 페이지 그룹을 지정
	$next_group = $end_page;
	if ($next_group > $total_page) $next_group = $total_page;
?>