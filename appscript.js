const SHEET_NAME_USERS = "users";

/**
 * GET endpoint
 * - action=register  → register user
 * - action=login     → login
 * - default          → ping
 */
function doGet(e) {
  const action = e.parameter.action || "";

  if (action === "register") return handleRegister(e);
  if (action === "login") return handleLogin(e);
  if (action === "order") return handleOrder(e); // 🔥 TAMBAHKAN INI

  return corsResponse({
    success: true,
    message: "API aktif",
  });
}

/**
 * REGISTER (GET)
 */
function handleRegister(e) {
  const id = e.parameter.id;
  const name = e.parameter.name;
  const email = e.parameter.email;
  const password = e.parameter.password; // sudah hash

  if (!id || !name || !email || !password) {
    return corsResponse({
      success: false,
      message: "Semua field wajib diisi",
    });
  }

  const sheet = SpreadsheetApp.getActive().getSheetByName(SHEET_NAME_USERS);
  sheet.appendRow([id, name, email, password, new Date()]);

  return corsResponse({
    success: true,
    message: "User registered",
  });
}

/**
 * LOGIN (GET)
 */
function handleLogin(e) {
  const email = e.parameter.email || "";
  const password = e.parameter.password || "";

  if (!email || !password) {
    return corsResponse({
      success: false,
      message: "Email & password wajib",
    });
  }

  const sheet = SpreadsheetApp.getActive().getSheetByName(SHEET_NAME_USERS);
  const data = sheet.getDataRange().getValues();
  const headers = data.shift();

  let foundUser = null;

  data.forEach((row) => {
    const user = {};
    headers.forEach((h, i) => (user[h] = row[i]));

    if (user.email === email && user.password === password) {
      foundUser = user;
    }
  });

  if (!foundUser) {
    return corsResponse({
      success: false,
      message: "Email atau password salah",
    });
  }

  delete foundUser.password;

  return corsResponse({
    success: true,
    user: foundUser,
  });
}

/**
 * ORDER (GET)
 * Contoh request:
 * ?action=order&user_name=yyy&user_email=zzz&product_name=aaa&product_price=25000
 */
function handleOrder(e) {
  const user_name = e.parameter.user_name || "";
  const user_email = e.parameter.user_email || "";
  const product_name = e.parameter.product_name || "";
  const product_price = Number(e.parameter.product_price || 0);
  const qty = Number(e.parameter.qty || 1);
  const note = e.parameter.note || "";

  if (
    !user_name ||
    !user_email ||
    !product_name ||
    !product_price
  ) {
    return corsResponse({
      success: false,
      message: "Field wajib belum lengkap.",
    });
  }

  const total_price = product_price * qty;
  const order_id = Date.now().toString(); // ID unik
  const order_date = new Date();

  const spreadsheet = SpreadsheetApp.getActive();
  let sheet = spreadsheet.getSheetByName("orders");
  if (!sheet) {
    sheet = spreadsheet.insertSheet("orders");
    sheet.appendRow([
      "order_id",
      "user_name",
      "user_email",
      "product_name",
      "product_price",
      "qty",
      "total_price",
      "order_date",
      "note",
    ]);
  }

  sheet.appendRow([
    order_id,
    user_name,
    user_email,
    product_name,
    product_price,
    qty,
    total_price,
    order_date,
    note,
  ]);

  return corsResponse({
    success: true,
    message: "Order berhasil dicatat.",
    order_id: order_id,
  });
}

/**
 * CORS helper
 */
function corsResponse(obj) {
  const output = ContentService.createTextOutput(JSON.stringify(obj));
  output.setMimeType(ContentService.MimeType.JSON);
  output.setHeader("Access-Control-Allow-Origin", "*");
  output.setHeader("Access-Control-Allow-Methods", "GET");
  output.setHeader("Access-Control-Allow-Headers", "Content-Type");
  return output;
}
