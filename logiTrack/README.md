# LogiTrack - Modern Logistics Management System

A comprehensive logistics management system built with PHP, MySQL, and modern web technologies.

## Features

- **Order Tracking**: Real-time tracking of shipments with detailed timeline
- **Order Registration**: Create and manage new shipping orders
- **User Authentication**: Secure login/registration system for customers and admins
- **Admin Dashboard**: Complete order management interface for administrators
- **Responsive Design**: Mobile-friendly interface using Tailwind CSS
- **API Integration**: RESTful API endpoints for all operations
- **Database Integration**: MySQL database with proper relationships and indexes

## Project Structure

```
logitech/
├── assets/
│   ├── css/
│   │   └── style.css          # Custom CSS styles
│   ├── js/
│   │   ├── main.js            # Main JavaScript functionality
│   │   └── admin.js           # Admin-specific JavaScript
│   └── images/                # Image assets
├── config/
│   └── database.php           # Database configuration and connection
├── functions/
│   ├── auth.php               # Authentication functions
│   └── orders.php             # Order management functions
├── includes/
│   ├── header.php             # Common header HTML
│   ├── footer.php             # Common footer HTML
│   └── modals.php             # Modal components
├── api/
│   ├── auth.php               # Authentication API endpoints
│   ├── orders.php             # Orders management API
│   └── track.php              # Order tracking API
├── index.php                  # Main homepage
├── admin.php                  # Admin dashboard
├── database.sql               # Database schema and sample data
└── README.md                  # This file
```

## Installation

### Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- XAMPP/WAMP/MAMP (for local development)

### Setup Instructions

1. **Clone/Download the project**
   ```bash
   # Place the project in your web server directory
   # For XAMPP: C:\xampp\htdocs\logitech
   ```

2. **Database Setup**
   - Start your MySQL server
   - Import the database schema:
     ```sql
     mysql -u root -p < database.sql
     ```
   - Or use phpMyAdmin to import `database.sql`

3. **Configure Database Connection**
   - Edit `config/database.php` if needed
   - Default settings:
     - Host: localhost
     - Database: logitrack
     - Username: root
     - Password: (empty)

4. **Access the Application**
   - Open your browser and navigate to:
     ```
     http://localhost/logitech
     ```

## Default Credentials

### Admin Account
- **Username**: admin@logitrack.com
- **Password**: password

### Sample Customer Account
- **Email**: john@example.com
- **Password**: password

## API Endpoints

### Authentication
- `POST /api/auth.php?action=login` - User login
- `POST /api/auth.php?action=register` - User registration
- `POST /api/auth.php?action=admin_login` - Admin login
- `GET /api/auth.php?action=logout` - Logout user

### Orders
- `GET /api/orders.php?action=list` - Get orders list (Admin)
- `POST /api/orders.php?action=create` - Create new order
- `PUT /api/orders.php?action=update` - Update order (Admin)
- `DELETE /api/orders.php?action=delete` - Delete order (Admin)
- `GET /api/orders.php?action=statistics` - Get order statistics (Admin)

### Tracking
- `GET /api/track.php?tracking_number=LT123456789` - Track order

## Features Overview

### Customer Features
- **Order Tracking**: Enter tracking number to view order status and timeline
- **Order Registration**: Create new shipping orders with detailed information
- **User Account**: Register and manage personal account
- **Responsive Interface**: Works on desktop, tablet, and mobile devices

### Admin Features
- **Dashboard Overview**: Statistics and order summary
- **Order Management**: View, edit, and delete orders
- **Status Updates**: Update order status and location
- **Search & Filter**: Find orders by tracking number, customer, or status
- **Real-time Updates**: Live statistics and order information

## Database Schema

### Tables
- **users**: User accounts (customers and admins)
- **orders**: Shipping orders with complete details
- **order_tracking**: Tracking history for each order
- **admin_notes**: Admin comments on orders

### Key Features
- Proper foreign key relationships
- Indexes for performance optimization
- Sample data for testing
- Secure password hashing

## Customization

### Styling
- Edit `assets/css/style.css` for custom styles
- Modify Tailwind configuration in header.php
- Update color scheme in CSS variables

### Functionality
- Add new features in `functions/` directory
- Extend API endpoints in `api/` directory
- Modify database schema as needed

### Configuration
- Update database settings in `config/database.php`
- Modify site settings in `includes/header.php`

## Security Features

- **Password Hashing**: Secure password storage using PHP's password_hash()
- **SQL Injection Prevention**: Prepared statements for all database queries
- **Session Management**: Secure session handling
- **Input Validation**: Server-side validation for all inputs
- **CSRF Protection**: CSRF token generation and validation

## Browser Support

- Chrome 60+
- Firefox 60+
- Safari 12+
- Edge 79+
- Mobile browsers (iOS Safari, Chrome Mobile)

## Technologies Used

- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Styling**: Tailwind CSS
- **Icons**: Font Awesome
- **Architecture**: MVC-like structure with separation of concerns

## Development

### Adding New Features

1. **Database Changes**: Update `database.sql` and run migrations
2. **Backend Logic**: Add functions in `functions/` directory
3. **API Endpoints**: Create/modify files in `api/` directory
4. **Frontend**: Update JavaScript in `assets/js/` directory
5. **Styling**: Modify CSS in `assets/css/style.css`

### Testing

- Use the sample data provided in `database.sql`
- Test all user flows (registration, login, order creation, tracking)
- Verify admin functionality with admin account
- Test responsive design on different devices

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Check MySQL server is running
   - Verify database credentials in `config/database.php`
   - Ensure database `logitrack` exists

2. **Permission Errors**
   - Check file permissions on web server
   - Ensure PHP has write access to necessary directories

3. **JavaScript Errors**
   - Check browser console for errors
   - Verify all JS files are loading correctly
   - Ensure API endpoints are accessible

### Support

For issues and questions:
1. Check the troubleshooting section
2. Review the code comments
3. Verify your PHP and MySQL versions meet requirements

## License

This project is for educational and development purposes. Feel free to modify and use as needed.

## Future Enhancements

- Email notifications for order updates
- SMS tracking updates
- Mobile app integration
- Advanced reporting and analytics
- Multi-language support
- Payment integration
- Inventory management
- Driver management system
