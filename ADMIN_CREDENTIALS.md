# Admin Account Credentials

## Created: December 23, 2025

This document contains the credentials for all regional and RTOM admin accounts created for the SLT Payment Reminder system.

---

## Regional Admins

Regional admins can see and manage **all customers and callers within their assigned region**, including all RTOMs under that region.

### 1. Metro Region Admin
- **Email:** `metro@slt.lk`
- **Password:** `Metro@123`
- **Admin ID:** RADM-METRO
- **Role:** region_admin
- **Region:** Metro Region
- **Access:** All RTOMs in Metro Region (CO, MA, ND, HK, KX, WT, RM)
- **Can manage:** 
  - All customers in Metro Region
  - All callers in Metro Region
  - View all RTOM activity within Metro Region

### 2. Region 1 Admin
- **Email:** `region01@slt.lk`
- **Password:** `Region01@123`
- **Admin ID:** RADM-R1
- **Role:** region_admin
- **Region:** Region 1
- **Access:** All RTOMs in Region 1 (AN, CW, GP, KA, KU, MT, NE, PO, KI)
- **Can manage:**
  - All customers in Region 1
  - All callers in Region 1
  - View all RTOM activity within Region 1

### 3. Region 2 Admin
- **Email:** `region02@slt.lk`
- **Password:** `Region02@123`
- **Admin ID:** RADM-R2
- **Role:** region_admin
- **Region:** Region 2
- **Access:** All RTOMs in Region 2 (AV, BA, BW, GA, HB, HA, KL, KG, RA)
- **Can manage:**
  - All customers in Region 2
  - All callers in Region 2
  - View all RTOM activity within Region 2

### 4. Region 3 Admin
- **Email:** `region03@slt.lk`
- **Password:** `Region03@123`
- **Admin ID:** RADM-R3
- **Role:** region_admin
- **Region:** Region 3
- **Access:** All RTOMs in Region 3 (AM, BT, JA, KM, KO, TR, VU)
- **Can manage:**
  - All customers in Region 3
  - All callers in Region 3
  - View all RTOM activity within Region 3

---

## RTOM Admins

RTOM admins can see and manage **only customers and callers within their specific RTOM**.

### 1. Colombo RTOM Admin (Metro Region)
- **Email:** `colombo@slt.lk`
- **Password:** `Colombo@123`
- **Admin ID:** RTOM-CO
- **Role:** rtom_admin
- **Region:** Metro Region (auto-assigned)
- **RTOM:** CO (Colombo Central)
- **Access:** Only CO RTOM within Metro Region
- **Can manage:**
  - Only customers in CO RTOM
  - Only callers assigned to CO RTOM
  - Create and manage callers for CO RTOM

### 2. Kandy RTOM Admin (Region 1)
- **Email:** `kandy@slt.lk`
- **Password:** `Kandy@123`
- **Admin ID:** RTOM-KA
- **Role:** rtom_admin
- **Region:** Region 1 (auto-assigned)
- **RTOM:** KA (Kandy)
- **Access:** Only KA RTOM within Region 1
- **Can manage:**
  - Only customers in KA RTOM
  - Only callers assigned to KA RTOM
  - Create and manage callers for KA RTOM

### 3. Galle RTOM Admin (Region 2)
- **Email:** `galle@slt.lk`
- **Password:** `Galle@123`
- **Admin ID:** RTOM-GA
- **Role:** rtom_admin
- **Region:** Region 2 (auto-assigned)
- **RTOM:** GA (Galle)
- **Access:** Only GA RTOM within Region 2
- **Can manage:**
  - Only customers in GA RTOM
  - Only callers assigned to GA RTOM
  - Create and manage callers for GA RTOM

### 4. Jaffna RTOM Admin (Region 3)
- **Email:** `jaffna@slt.lk`
- **Password:** `Jaffna@123`
- **Admin ID:** RTOM-JA
- **Role:** rtom_admin
- **Region:** Region 3 (auto-assigned)
- **RTOM:** JA (Jaffna)
- **Access:** Only JA RTOM within Region 3
- **Can manage:**
  - Only customers in JA RTOM
  - Only callers assigned to JA RTOM
  - Create and manage callers for JA RTOM

---

## Existing Accounts (Reference)

### Superadmin
- **Email:** `superadmin@slt.lk` or `super@test.com`
- **Password:** `Admin@123` or `password`
- **Access:** Full system access

### Uploader
- **Email:** `uploader@slt.lk`
- **Password:** `Upload@123`
- **Access:** Upload customer data only

---

## Access Control Summary

| Account Type | Can View | Can Create | Scope |
|--------------|----------|------------|-------|
| **Superadmin** | All customers, All callers, All admins | All types of admins, callers | Entire system |
| **Region Admin** | Customers in region, Callers in region | Callers in their region | Single region, all RTOMs |
| **RTOM Admin** | Customers in RTOM, Callers in RTOM | Callers in their RTOM | Single RTOM only |
| **Uploader** | None | N/A | Data upload only |

---

## Security Notes

1. **Change default passwords** on first login
2. All passwords use bcrypt hashing
3. All accounts are set to **active** status
4. Region/RTOM assignments are **immutable** after creation (only superadmin can modify)
5. RTOM admins have their region automatically assigned based on RTOM code

---

## Testing Login

You can test these accounts by:

1. **Web Application:**
   - Go to login page
   - Enter email and password
   - Verify you see only data for your assigned region/RTOM

2. **API Testing:**
   ```bash
   # Login as regional admin
   curl -X POST http://localhost:8000/api/login \
     -H "Content-Type: application/json" \
     -d '{"email": "metro@slt.lk", "password": "Metro@123"}'
   
   # Use the token to access customers
   curl -X GET http://localhost:8000/api/customers \
     -H "Authorization: Bearer YOUR_TOKEN"
   ```

---

## For Production Use

**IMPORTANT:** Before deploying to production:

1. ✅ Change all default passwords to secure passwords
2. ✅ Enable password expiry policies
3. ✅ Set up email notifications for new admin creation
4. ✅ Enable two-factor authentication (if available)
5. ✅ Review and audit all admin access logs
6. ✅ Document password reset procedures

---

**Document Generated:** December 23, 2025  
**Total Accounts Created:** 8 (4 Regional + 4 RTOM Admins)  
**Status:** All accounts active and ready for use
