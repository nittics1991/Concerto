//コミット・エラー・キャッシュヒット

SELECT Z.* 
	, CASE WHEN day_interval IS NULL THEN 0
		ELSE ROUND(CAST((change_heap_blks_read / day_interval) AS NUMERIC) , 0) 
		END AS change_heap_blks_read_day 

	, CASE WHEN day_interval IS NULL THEN 0
		ELSE ROUND(CAST((change_heap_blks_hit / day_interval) AS NUMERIC) , 0) 
		END AS change_heap_blks_hit_day 

	, CASE WHEN day_interval IS NULL THEN 0
		ELSE ROUND(CAST((change_idx_blks_read / day_interval) AS NUMERIC) , 0) 
		END AS change_idx_blks_read_day 

	, CASE WHEN day_interval IS NULL THEN 0
		ELSE ROUND(CAST((change_idx_blks_hit / day_interval) AS NUMERIC) , 0) 
		END AS change_idx_blks_hit_day 

	, CASE WHEN day_interval IS NULL THEN 0
		ELSE ROUND(CAST((change_toast_blks_read / day_interval) AS NUMERIC) , 0) 
		END AS change_toast_blks_read_day 

	, CASE WHEN day_interval IS NULL THEN 0
		ELSE ROUND(CAST((change_toast_blks_hit / day_interval) AS NUMERIC) , 0) 
		END AS change_toast_blks_hit_day 

	, CASE WHEN day_interval IS NULL THEN 0
		ELSE ROUND(CAST((change_tidx_blks_read / day_interval) AS NUMERIC) , 0) 
		END AS change_tidx_blks_read_day 

	, CASE WHEN day_interval IS NULL THEN 0
		ELSE ROUND(CAST((change_tidx_blks_hit / day_interval) AS NUMERIC) , 0) 
		END AS change_tidx_blks_hit_day 


	, CASE WHEN (change_heap_blks_read + change_heap_blks_hit) = 0 THEN 0
		ELSE ROUND(CAST((100 * change_heap_blks_hit / (change_heap_blks_read + change_heap_blks_hit)) AS NUMERIC) , 2) 
		END AS change_heap_blks_hit_ratio 

	, CASE WHEN (change_idx_blks_read + change_idx_blks_hit) = 0 THEN 0
		ELSE ROUND(CAST((100 * change_idx_blks_hit / (change_idx_blks_read + change_idx_blks_hit)) AS NUMERIC) , 2) 
		END AS change_idx_blks_hit_ratio 

	, CASE WHEN (change_toast_blks_read + change_toast_blks_hit) = 0 THEN 0
		ELSE ROUND(CAST((100 * change_toast_blks_hit / (change_toast_blks_read + change_toast_blks_hit)) AS NUMERIC) , 2) 
		END AS change_toast_blks_hit_ratio 

	, CASE WHEN (change_tidx_blks_read + change_tidx_blks_hit) = 0 THEN 0
		ELSE ROUND(CAST((100 * change_tidx_blks_hit / (change_tidx_blks_read + change_tidx_blks_hit)) AS NUMERIC) , 2) 
		END AS change_tidx_blks_hit_ratio 

FROM
	(SELECT A.* 
		, B.schemaname, B.relname, B.heap_blks_read, B.heap_blks_hit, B.idx_blks_read, B.idx_blks_hit 
		, B.toast_blks_read, B.toast_blks_hit, B.tidx_blks_read, B.tidx_blks_hit 
		, COALESCE((B.heap_blks_read - C.heap_blks_read), 0) AS change_heap_blks_read 
		, COALESCE((B.heap_blks_hit - C.heap_blks_hit), 0) AS change_heap_blks_hit 
		, COALESCE((B.idx_blks_read - C.idx_blks_read), 0) AS change_idx_blks_read 
		, COALESCE((B.idx_blks_hit - C.idx_blks_hit), 0) AS change_idx_blks_hit 
		, COALESCE((B.toast_blks_read - C.toast_blks_read), 0) AS change_toast_blks_read 
		, COALESCE((B.toast_blks_hit - C.toast_blks_hit), 0) AS change_toast_blks_hit 
		, COALESCE((B.tidx_blks_read - C.tidx_blks_read), 0) AS change_tidx_blks_read 
		, COALESCE((B.tidx_blks_hit - C.tidx_blks_hit), 0) AS change_tidx_blks_hit 
		, (B.ts - C.ts) AS interval
		, EXTRACT('day' from (B.ts - C.ts)) AS day_interval 
		
	FROM pgperf.snapshot A 
	LEFT JOIN 
		(SELECT BA.sid, BA.schemaname, BA.relname, BA.heap_blks_read, BA.heap_blks_hit, BA.idx_blks_read, BA.idx_blks_hit 
			, BA.toast_blks_read, BA.toast_blks_hit, BA.tidx_blks_read, BA.tidx_blks_hit 
			, BB.ts 
		FROM pgperf.snapshot_pg_statio_user_tables BA 
			LEFT JOIN pgperf.snapshot BB 
				ON BB.sid = BA.sid 
		) B 
		ON B.sid = A.sid
	LEFT JOIN 
		(SELECT CA.sid, CA.schemaname, CA.relname, CA.heap_blks_read, CA.heap_blks_hit, CA.idx_blks_read, CA.idx_blks_hit 
			, CA.toast_blks_read, CA.toast_blks_hit, CA.tidx_blks_read, CA.tidx_blks_hit 
			, CB.ts 
		FROM pgperf.snapshot_pg_statio_user_tables CA 
			LEFT JOIN pgperf.snapshot CB 
				ON CB.sid = CA.sid 
		) C 
		ON C.sid = A.sid - 1 
			AND C.schemaname = B.schemaname AND C.relname = B.relname 
	) Z 
ORDER BY Z.sid, Z.schemaname, Z.relname 
