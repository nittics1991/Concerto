//コミット・エラー・キャッシュヒット

SELECT Z.* 
	, CASE WHEN day_interval IS NULL THEN 0
		ELSE ROUND(CAST((change_xact_commit / day_interval) AS NUMERIC) , 0) 
		END AS change_xact_commit_day 

	, CASE WHEN day_interval IS NULL THEN 0
		ELSE ROUND(CAST((change_xact_rollback / day_interval) AS NUMERIC) , 0) 
		END AS change_xact_rollback_day 

	, CASE WHEN day_interval IS NULL THEN 0
		ELSE ROUND(CAST((change_blks_read / day_interval) AS NUMERIC) , 0) 
		END AS change_blks_read_day 

	, CASE WHEN day_interval IS NULL THEN 0
		ELSE ROUND(CAST((change_blks_hit / day_interval) AS NUMERIC) , 0) 
		END AS change_blks_hit_day 


	, CASE WHEN (change_blks_read + change_blks_hit) = 0 THEN 0
		ELSE ROUND(CAST((100 * change_blks_hit / (change_blks_read + change_blks_hit)) AS NUMERIC) , 2) 
		END AS cache_hit_ratio 

FROM
	(SELECT A.* 
		, B.datname, B.xact_commit, B.xact_rollback, B.blks_read, B.blks_hit 
		, COALESCE((B.xact_commit - C.xact_commit), 0) AS change_xact_commit 
		, COALESCE((B.xact_rollback - C.xact_rollback), 0) AS change_xact_rollback 
		, COALESCE((B.blks_read - C.blks_read), 0) AS change_blks_read 
		, COALESCE((B.blks_hit - C.blks_hit), 0) AS change_blks_hit 
		, (B.ts - C.ts) AS interval
		, EXTRACT('day' from (B.ts - C.ts)) AS day_interval 
		
	FROM pgperf.snapshot A 
	LEFT JOIN 
		(SELECT BA.sid, BA.datname, BA.xact_commit, BA.xact_rollback, BA.blks_read, BA.blks_hit 
			, BB.ts 
		FROM pgperf.snapshot_pg_stat_database BA 
			LEFT JOIN pgperf.snapshot BB 
				ON BB.sid = BA.sid 
		WHERE datname <> 'template0' AND datname <> 'template1' 
		) B 
		ON B.sid = A.sid 
	LEFT JOIN 
		(SELECT CA.sid, CA.datname, CA.xact_commit, CA.xact_rollback, CA.blks_read, CA.blks_hit 
			, CB.ts 
		FROM pgperf.snapshot_pg_stat_database CA 
			LEFT JOIN pgperf.snapshot CB 
				ON CB.sid = CA.sid 
		WHERE datname <> 'template0' AND datname <> 'template1' 
		) C 
		ON C.sid = A.sid - 1
	) Z 
ORDER BY Z.sid 

