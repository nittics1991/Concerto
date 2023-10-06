//ディスクサイズ

SELECT Z.* 
	, CASE WHEN day_interval IS NULL THEN 0
		ELSE ROUND(CAST((change_pg_relation_size / day_interval) AS NUMERIC) , 0) 
		END AS change_pg_relation_size_day 

	, CASE WHEN day_interval IS NULL THEN 0
		ELSE ROUND(CAST((change_pg_total_relation_size / day_interval) AS NUMERIC) , 0) 
		END AS change_pg_total_relation_size_day 

FROM
	(SELECT A.* 
		, B.schemaname, B.relname, B.pg_relation_size, B.pg_total_relation_size 
		, COALESCE((B.pg_relation_size - C.pg_relation_size), 0) AS change_pg_relation_size 
		, COALESCE((B.pg_total_relation_size - C.pg_total_relation_size), 0) AS change_pg_total_relation_size 
		, (B.ts - C.ts) AS interval
		, EXTRACT('day' from (B.ts - C.ts)) AS day_interval 
		
	FROM pgperf.snapshot A 
	LEFT JOIN 
		(SELECT BA.sid, BA.schemaname, BA.relname, BA.pg_relation_size, BA.pg_total_relation_size 
			, BB.ts 
		FROM pgperf.snapshot_pg_relation_size BA 
			LEFT JOIN pgperf.snapshot BB 
				ON BB.sid = BA.sid 
		) B 
		ON B.sid = A.sid
	LEFT JOIN 
		(SELECT CA.sid, CA.schemaname, CA.relname, CA.pg_relation_size, CA.pg_total_relation_size 
			, CB.ts 
		FROM pgperf.snapshot_pg_relation_size CA 
			LEFT JOIN pgperf.snapshot CB 
				ON CB.sid = CA.sid 
		) C 
		ON C.sid = A.sid - 1 
			AND C.schemaname = B.schemaname AND C.relname = B.relname 
	) Z 
ORDER BY Z.sid, Z.schemaname, Z.relname 

