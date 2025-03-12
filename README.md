# **Read Time Estimator**

**Read Time Estimator** is a lightweight and optimized WordPress plugin that automatically estimates and displays the reading time of posts in a subtle and user-friendly way.

---

## **Disclaimer**

This plugin is provided "as is" without any warranties or guarantees. The author disclaims all warranties, expressed or implied, including but not limited to merchantability, fitness for a particular purpose, ownership, or non-infringement of third-party rights. Use of this plugin is at the user's own risk.

---

## **Description**

Read Time Estimator enhances user experience by providing an estimated reading time for each post. The estimation is based on an average reading speed and is displayed automatically without modifying the original content layout.

---

## **Installation**

1. Download the plugin and upload it to the `/wp-content/plugins/read-time-estimator` directory, or install it directly through the WordPress plugin repository.
2. Activate the plugin from the **Plugins** screen in WordPress.
3. The estimated reading time will now be automatically displayed for all posts.

---

## **Usage**

- Once activated, the plugin calculates the estimated reading time for each post and inserts it before the content.
- To manually display the reading time anywhere in your theme, use the shortcode:  
  ```html
  [read_time]
  ```
- For developers, use the function:
  ```php
  echo pixovoid_get_read_time($post_id);
  ```

---

## **Features**

✔️ **Automatic Read Time Calculation** – Estimates reading time based on post content.  
✔️ **Subtle & Responsive Display** – Blends seamlessly with most themes.  
✔️ **Custom Shortcode** – `[read_time]` for manual placement.  
✔️ **Developer-Friendly Functions** – Easily retrieve reading time with `pixovoid_get_read_time()`.  
✔️ **Lightweight & Optimized** – Minimal impact on performance.  

### **Function Reference**

#### `pixovoid_calculate_read_time($content)`

Calculates the estimated reading time based on the content.

- **Parameter:**  
  - `$content` *(string)* – The post content.
- **Returns:**  
  - *(int)* – Estimated reading time in minutes.

---

## **License**

This plugin is licensed under the **MIT License**. See the [LICENSE](LICENSE) file for details.

---

## **Author**

Developed by **[PixoVoid.net](https://pixovoid.net/)**.

---

### 🚀 **Enjoy a better reading experience with Read Time Estimator!** 🚀