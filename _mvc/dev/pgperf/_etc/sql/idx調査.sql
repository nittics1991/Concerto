//最新SIDのキャッシュヒット順（sid=手入力）

SELECT Z.* 
	, CASE WHEN day_interval IS NULL THEN 0
		ELSE ROUND(CAST((change_seq_scan / day_interval) AS NUMERIC) , 0) 
		END AS change_seq_scan_day 

	, CASE WHEN day_interval IS NULL THEN 0
		ELSE ROUND(CAST((change_seq_tup_read / day_interval) AS NUMERIC) , 0) 
		END AS change_seq_tup_read_day 

	, CASE WHEN day_interval IS NULL THEN 0
		ELSE ROUND(CAST((change_idx_scan / day_interval) AS NUMERIC) , 0) 
		END AS change_idx_scan_day 

	, CASE WHEN day_interval IS NULL THEN 0
		ELSE ROUND(CAST((change_idx_tup_fetch / day_interval) AS NUMERIC) , 0) 
		END AS change_idx_tup_fetch_day 

	, CASE WHEN day_interval IS NULL THEN 0
		ELSE ROUND(CAST((change_n_tup_ins / day_interval) AS NUMERIC) , 0) 
		END AS change_n_tup_ins_day 

	, CASE WHEN day_interval IS NULL THEN 0
		ELSE ROUND(CAST((change_n_tup_upd / day_interval) AS NUMERIC) , 0) 
		END AS change_n_tup_upd_day 

	, CASE WHEN day_interval IS NULL THEN 0
		ELSE ROUND(CAST((change_n_tup_del / day_interval) AS NUMERIC) , 0) 
		END AS change_n_tup_del_day 


	, CASE WHEN (idx_scan + seq_scan) = 0 THEN 0
		ELSE ROUND(CAST((100 * idx_scan / (idx_scan + seq_scan)) AS NUMERIC) , 2) 
		END AS idx_scan_ratio 

FROM
	(SELECT A.* 
		, B.schemaname, B.relname, B.seq_scan, B.seq_tup_read, B.idx_scan, B.idx_tup_fetch 
		, COALESCE((B.seq_scan - C.seq_scan), 0) AS change_seq_scan 
		, COALESCE((B.seq_tup_read - C.seq_tup_read), 0) AS change_seq_tup_read 
		, COALESCE((B.idx_scan - C.idx_scan), 0) AS change_idx_scan 
		, COALESCE((B.idx_tup_fetch - C.idx_tup_fetch), 0) AS change_idx_tup_fetch 
		, COALESCE((B.n_tup_ins - C.n_tup_ins), 0) AS change_n_tup_ins 
		, COALESCE((B.n_tup_upd - C.n_tup_upd), 0) AS change_n_tup_upd 
		, COALESCE((B.n_tup_del - C.n_tup_del), 0) AS change_n_tup_del 
		, (B.ts - C.ts) AS interval
		, EXTRACT('day' from (B.ts - C.ts)) AS day_interval 
		
	FROM pgperf.snapshot A 
	LEFT JOIN 
		(SELECT BA.sid, BA.schemaname, BA.relname, BA.seq_scan, BA.seq_tup_read, BA.idx_scan, BA.idx_tup_fetch 
			, BA.n_tup_ins, BA.n_tup_upd, BA.n_tup_del 
			, BB.ts 
		FROM pgperf.snapshot_pg_stat_user_tables BA 
			LEFT JOIN pgperf.snapshot BB 
				ON BB.sid = BA.sid 
		) B 
		ON B.sid = A.sid
	LEFT JOIN 
		(SELECT CA.sid, CA.schemaname, CA.relname, CA.seq_scan, CA.seq_tup_read, CA.idx_scan, CA.idx_tup_fetch 
			, CA.n_tup_ins, CA.n_tup_upd, CA.n_tup_del 
			, CB.ts 
		FROM pgperf.snapshot_pg_stat_user_tables CA 
			LEFT JOIN pgperf.snapshot CB 
				ON CB.sid = CA.sid 
		) C 
		ON C.sid = A.sid - 1 
			AND C.schemaname = B.schemaname AND C.relname = B.relname 
	) Z 

WHERE Z.sid = 2 
AND Z.schemaname = 'public'


ORDER BY idx_scan_ratio, Z.sid, Z.schemaname, Z.relname 
