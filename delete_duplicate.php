<>?php

// SQL QUERY TO CHECK FOR DUPLICATE DATA

"select count(id),oid from pfi_revenue GROUP BY oid HAVING (COUNT(*) > 1)"

// DELETE ALL DUPLICATE

"DELETE FROM pfi_revenue  
WHERE id IN (SELECT * 
             FROM (SELECT id FROM pfi_revenue 
                   GROUP BY oid HAVING (COUNT(*) > 1)
                  ) AS A
            )"
?