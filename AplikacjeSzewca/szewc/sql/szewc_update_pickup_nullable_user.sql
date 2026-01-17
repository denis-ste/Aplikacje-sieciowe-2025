--CLIENT nie ustala terminu odbioru, WORKER ustala później.
-- orders.pickup_date dopuszcza NULL.

ALTER TABLE `orders`
  MODIFY `pickup_date` DATE NULL DEFAULT NULL;
