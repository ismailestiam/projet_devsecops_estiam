const express = require('express');
const cors = require('cors');
const path = require('path');

const app = express();
const PORT = process.env.PORT || 3000;

// Middleware
app.use(cors());
app.use(express.json());
app.use(express.static(path.join(__dirname, 'public')));

// Routes API
app.get('/api/health', (req, res) => {
    res.json({
        status: 'OK',
        service: 'Node.js Express API',
        timestamp: new Date().toISOString(),
        uptime: process.uptime()
    });
});

app.get('/api/info', (req, res) => {
    res.json({
        service: 'Node.js Express Application',
        version: '1.0.0',
        environment: process.env.NODE_ENV || 'development',
        node_version: process.version,
        platform: process.platform,
        architecture: process.arch,
        memory_usage: process.memoryUsage(),
        features: [
            'RESTful API',
            'CORS enabled',
            'Static file serving',
            'Health monitoring',
            'DevSecOps ready'
        ]
    });
});

app.get('/api/users', (req, res) => {
    // Simulation d'une base de données d'utilisateurs
    const users = [
        { id: 1, name: 'Alice Dupont', email: 'alice@example.com', role: 'admin' },
        { id: 2, name: 'Bob Martin', email: 'bob@example.com', role: 'user' },
        { id: 3, name: 'Claire Durand', email: 'claire@example.com', role: 'user' },
        { id: 4, name: 'David Leroy', email: 'david@example.com', role: 'moderator' }
    ];
    
    res.json({
        success: true,
        count: users.length,
        data: users
    });
});

app.post('/api/users', (req, res) => {
    const { name, email, role } = req.body;
    
    if (!name || !email) {
        return res.status(400).json({
            success: false,
            error: 'Name and email are required'
        });
    }
    
    const newUser = {
        id: Date.now(),
        name,
        email,
        role: role || 'user',
        created_at: new Date().toISOString()
    };
    
    res.status(201).json({
        success: true,
        message: 'User created successfully',
        data: newUser
    });
});

// Route pour servir l'application frontend
app.get('/', (req, res) => {
    res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

// Gestion des erreurs 404
app.use('*', (req, res) => {
    res.status(404).json({
        success: false,
        error: 'Route not found',
        available_routes: [
            'GET /',
            'GET /api/health',
            'GET /api/info',
            'GET /api/users',
            'POST /api/users'
        ]
    });
});

// Gestion des erreurs globales
app.use((err, req, res, next) => {
    console.error(err.stack);
    res.status(500).json({
        success: false,
        error: 'Internal server error'
    });
});

app.listen(PORT, '0.0.0.0', () => {
    console.log(`🚀 Node.js Express server running on port ${PORT}`);
    console.log(`📡 Health check: http://localhost:${PORT}/api/health`);
    console.log(`📊 Info endpoint: http://localhost:${PORT}/api/info`);
    console.log(`👥 Users API: http://localhost:${PORT}/api/users`);
});

module.exports = app;

