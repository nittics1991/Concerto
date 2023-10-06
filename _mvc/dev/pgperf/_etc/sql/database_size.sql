//データベースサイズ

SELECT Z.* 
	, CASE WHEN day_interval IS NULL THEN 0
		ELSE ROUND(CAST((change_volume / day_interval) AS NUMERIC) , 0) 
		END AS change_volume_day 

FROM
	(SELECT A.* 
		, B.datname, B.pg_database_size 
		, COALESCE(ROUND(CAST((B.pg_database_size - C.pg_database_size) AS NUMERIC), 0), 0) AS change_volume 
		, (B.ts - C.ts) AS interval
		, EXTRACT('day' from (B.ts - C.ts)) AS day_interval 
		
		
	FROM pgperf.snapshot A 
	LEFT JOIN 
		(SELECT BA.sid, BA.datname, BA.pg_database_size 
			, BB.ts 
		FROM pgperf.snapshot_pg_database_size BA 
			LEFT JOIN pgperf.snapshot BB 
				ON BB.sid = BA.sid 
		WHERE datname <> 'template0' AND datname <> 'template1' 
		) B 
		ON B.sid = A.sid 
	LEFT JOIN 
		(SELECT CA.sid, CA.datname, CA.pg_database_size 
			, CB.ts 
		FROM pgperf.snapshot_pg_database_size CA 
			LEFT JOIN pgperf.snapshot CB 
				ON CB.sid = CA.sid 
		WHERE datname <> 'template0' AND datname <> 'template1' 
		) C 
		ON C.sid = A.sid - 1
	) Z 
ORDER BY Z.sid, Z.datname 

