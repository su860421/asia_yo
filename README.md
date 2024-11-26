## 使用何種SOILID及設計模式

- 單一職責原則 (拆分為小功能)
- 依賴倒置原則 (依賴OrderInterface)

- 策略模式 (方便擴展功能)
- 轉換器模式 (數值轉換)



## 資料庫測試 一

```sql
SELECT orders.bnb_id, bnbs.name AS bnb_name, SUM(orders.amount) AS total_amount 
FROM orders JOIN bnbs ON orders.bnb_id = bnbs.id 
WHERE orders.currency = 'TWD' AND orders.created_at BETWEEN '2023-05-01 00:00:00' AND '2023-05-31 23:59:59' 
GROUP BY orders.bnb_id, bnbs.name 
ORDER BY total_amount DESC LIMIT 10;
```

## 資料庫測試 二

判斷方式:
- 判斷是否有加入合適的index
- 資料量級是否過大
- 使用AI工具協助確認是否該段SQL是否有可優化的地方

優化方式:
- 適當的地方加入index
- 量級過大時,評估是否可執行過期資料移除
- SELECT 值,只取出需要的參數
- 使用AI工具協助
