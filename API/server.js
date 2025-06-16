const fs = require('fs');
const https = require('https');
const express = require('express');
const bcrypt = require('bcrypt');
const { Pool } = require('pg');
const multer = require('multer');
const cors = require('cors');

const upload = multer({ dest: 'uploads/' });
const app = express();
app.use(express.json());

app.use(cors({
    origin: 'https://programing-olympiad.ddns.net',
    methods: ['GET', 'POST', 'PUT', 'DELETE'],
    allowedHeaders: ['Content-Type', 'Authorization'],
}));

const options = {
    key: fs.readFileSync('./certs/programing-olympiad.ddns.net-key.pem'),
    cert: fs.readFileSync('./certs/programing-olympiad.ddns.net-crt.pem'),
    ca: fs.readFileSync('./certs/programing-olympiad.ddns.net-chain.pem'),
};

const pool = new Pool({
    user: 'postgres',
    host: 'localhost',
    database: 'db_olympiad',
    password: 'admin',
    port: 5432,
});

app.post('/api/login', async (req, res) => {
    const { name, password } = req.body;
    console.log(`POST /api/login - name: ${name}`);

    try {
        const result = await pool.query(
            'SELECT get_user_password($1) AS password',
            [name]
        );

        if (result.rows.length === 0 || !result.rows[0].password) {
            console.log(`Користувача ${name} не знайдено`);
            return res.status(401).json({
                success: false,
                message: 'Користувача не знайдено',
            });
        }

        const hashedPassword = result.rows[0].password;
        const passwordMatch = await bcrypt.compare(password, hashedPassword);

        if (!passwordMatch) {
            console.log(`Невірний пароль для користувача ${name}`);
            return res.status(401).json({
                success: false,
                message: 'Невірний пароль',
            });
        }

        console.log(`Успішний вхід користувача ${name}`);
        return res.json({ success: true });

    } catch (error) {
        console.error('Login error:', error);
        return res.status(500).json({
            success: false,
            message: 'Помилка сервера',
        });
    }
});

app.get('/api/participants', async (req, res) => {
    console.log('GET /api/participants');
    try {
        const result = await pool.query('SELECT * FROM participants_view');
        return res.json({ data: result.rows });
    } catch (error) {
        console.error('Get participants from participants_view error:', error);
        return res.status(500).json({
            success: false,
            message: 'Помилка сервера',
        });
    }
});

app.get('/api/submissions', async (req, res) => {
    console.log('GET /api/submissions');
    try {
        const result = await pool.query('SELECT * FROM submissions_view');
        return res.json({ data: result.rows });
    } catch (error) {
        console.error('Get submissions from submissions_view error:', error);
        return res.status(500).json({
            success: false,
            message: 'Помилка сервера',
        });
    }
});

app.get('/api/teachers', async (req, res) => {
    console.log('GET /api/teachers');
    try {
        const result = await pool.query('SELECT * FROM teachers_view');
        return res.json({ data: result.rows });
    } catch (error) {
        console.error('Get teachers from teachers_view error:', error);
        return res.status(500).json({
            success: false,
            message: 'Помилка сервера',
        });
    }
});

app.get('/api/languages', async (req, res) => {
    console.log('GET /api/languages');
    try {
        const result = await pool.query('SELECT * FROM languages_view');
        return res.json({ data: result.rows });
    } catch (error) {
        console.error('Get languages from view error:', error);
        return res.status(500).json({
            success: false,
            message: 'Помилка сервера',
        });
    }
});

app.get('/api/teachers/:id', async (req, res) => {
    const teacherId = req.params.id;
    console.log(`GET /api/teachers/${teacherId}`);
    try {
        const result = await pool.query('SELECT * FROM get_teacher_by_id($1)', [teacherId]);

        if (result.rows.length === 0) {
            console.log(`Викладача з id ${teacherId} не знайдено`);
            return res.status(404).json({
                success: false,
                message: 'Викладач не знайдений'
            });
        }

        return res.json({
            success: true,
            data: result.rows
        });

    } catch (error) {
        console.error('Get teacher by id error:', error);
        return res.status(500).json({
            success: false,
            message: 'Помилка сервера',
        });
    }
});

app.get('/api/participants/:id', async (req, res) => {
    const participantId = req.params.id;
    console.log(`GET /api/participants/${participantId}`);
    try {
        const result = await pool.query('SELECT * FROM get_participant_by_id($1)', [participantId]);

        if (result.rows.length === 0) {
            console.log(`Учасника з id ${participantId} не знайдено`);
            return res.status(404).json({
                success: false,
                message: 'Учасник не знайдений',
            });
        }

        return res.json({
            success: true,
            data: result.rows,
        });

    } catch (error) {
        console.error('Get participant by id error:', error);
        return res.status(500).json({
            success: false,
            message: 'Помилка сервера',
        });
    }
});

app.get('/api/submissions/:id', async (req, res) => {
    const submissionId = req.params.id;
    console.log(`GET /api/submissions/${submissionId}`);
    try {
        const result = await pool.query('SELECT * FROM get_submission_by_id($1)', [submissionId]);

        if (result.rows.length === 0) {
            console.log(`Спробу з id ${submissionId} не знайдено`);
            return res.status(404).json({
                success: false,
                message: 'Спроба не знайдена',
            });
        }

        return res.json({
            success: true,
            data: result.rows[0],
        });

    } catch (error) {
        console.error('Get submission by id error:', error);
        return res.status(500).json({
            success: false,
            message: 'Помилка сервера',
        });
    }
});

app.get('/api/filter/:pageType', async (req, res) => {
    const { pageType } = req.params;
    console.log(`GET /api/filter/${pageType} з параметрами:`, req.query);

    const {
        full_name, language, score_min, score_max, date_from, date_to,
        teacher_id, class: className, school, has_score, teacher_full_name
    } = req.query;

    try {
        let query = '';
        let params = [];

        switch (pageType) {
            case 'submissions':
                query = 'SELECT * FROM filter_submissions($1, $2, $3, $4, $5, $6, $7)';
                params = [
                    full_name || null,
                    language || null,
                    score_min || null,
                    score_max || null,
                    date_from || null,
                    date_to || null,
                    has_score || null
                ];
                break;

            case 'participants':
                query = 'SELECT * FROM filter_participants($1, $2, $3, $4)';
                params = [
                    full_name || null,
                    school || null,
                    className || null,
                    teacher_full_name || null
                ];
                break;

            case 'teachers':
                query = 'SELECT * FROM filter_teachers($1, $2)';
                params = [
                    full_name || null,
                    school || null
                ];
                break;

            default:
                console.log(`Невідомий тип фільтрації: ${pageType}`);
                return res.status(400).json({  message: 'Невідомий тип фільтрації' });
        }

        const result = await pool.query(query, params);
        return res.json(result.rows);

    } catch (error) {
        console.error('Filter error:', error.message);
        return res.status(500).json({
            success: false,
            message: error.message,
        });
    }
});

app.post('/api/import/:type', upload.single('json_file'), async (req, res) => {
    const { type } = req.params;
    console.log(`POST /api/import/${type}`);

    const tableMap = {
        participants: 'participants',
        teachers: 'teachers',
        submissions: 'submissions',
    };

    const table = tableMap[type];

    if (!table) {
        console.log(`Невідомий тип імпорту: ${type}`);
        return res.status(400).json({ success: false, message: 'Невідомий тип імпорту.' });
    }

    if (!req.file) {
        console.log('Файл для імпорту не завантажено');
        return res.status(400).json({ success: false, message: 'Файл не завантажено.' });
    }

    try {
        const raw = fs.readFileSync(req.file.path, 'utf8');
        const data = JSON.parse(raw);

        if (!Array.isArray(data)) {
            console.log('Невірний формат JSON – очікується масив');
            return res.status(400).json({ success: false, message: 'Очікується масив обʼєктів у JSON.' });
        }

        await pool.query('SELECT import_json_array($1, $2::jsonb)', [
            type,
            JSON.stringify(data)
        ]);

        fs.unlinkSync(req.file.path);
        console.log(`Імпорт у таблицю "${table}" успішний`);

        return res.json({ success: true, message: `Імпорт у таблицю "${table}" успішний.` });

    } catch (error) {
        console.error('Import API error:', error);
        return res.status(500).json({ success: false, message: `Помилка: ${error.message}` });
    }
});

app.get('/api/analytics/submissions', async (req, res) => {
    try {
        const row = await pool.query('SELECT public.get_submissions_analytics() AS submissions');
        const data = row.rows[0].submissions;

        res.json({
            success: true,
            general: data.general,
            languages: data.languages,
            classes: data.classes,
            dynamics: data.dynamics,
            topParticipants: data.top_participants
        });
    } catch (error) {
        console.error('Error in /api/analytics/submissions:', error);
        res.status(500).json({ success: false, message: 'Server error' });
    }
});
app.get('/api/analytics/teachers', async (req, res) => {
    try {
        const row = await pool.query('SELECT get_teachers_analytics() AS analytics');
        let analytics = row.rows[0].analytics;

        if (typeof analytics === 'string') {
            analytics = JSON.parse(analytics);
        }

        res.json({
            success: true,
            general: analytics.general ?? {},
            averageScore: analytics.average_score_all_teachers ?? 0,
            topTeachers: analytics.top_teachers_avg_score ?? [],
            bestTeacher: analytics.best_teacher ?? null,
            activityOverTime: analytics.teacher_activity_over_time ?? [],
            minScores: analytics.teachers_min_scores ?? []
        });
    } catch (error) {
        console.error('Error in /api/analytics/teachers:', error);
        res.status(500).json({ success: false, message: 'Server error' });
    }
});


app.get('/api/analytics/participants', async (req, res) => {
    try {
        const row = await pool.query('SELECT get_participants_analytics() AS analytics');
        let data = row.rows[0].analytics;

        if (typeof data === 'string') {
            data = JSON.parse(data);
        }

        res.json({
            success: true,
            general: data.general ?? {},
            topByAvg: data.top5_by_avg_score ?? [],
            topByMax: data.top5_by_max_score ?? [],
            mostPopularLanguage: data.most_popular_language ?? {},
            avgScoreAll: data.avg_score_all ?? {},
            totalScoreSum: data.total_score_sum ?? {},
            countMoreThan5Submissions: data.count_more_than_5_submissions ?? {},
            top3BySubmissionCount: data.top3_by_submission_count ?? []
        });
    } catch (error) {
        console.error('Error in /api/analytics/participants:', error);
        res.status(500).json({ success: false, message: 'Server error' });
    }
});

app.get('/api/analytics/tests', async (req, res) => {
    try {
        const row = await pool.query('SELECT get_tests_analytics() AS analytics');
        let data = row.rows[0].analytics;

        if (typeof data === 'string') {
            data = JSON.parse(data);
        }

        res.json({
            success: true,
            highScorePercent: data.high_score_percent ?? [],
            leastAttemptedTests: data.least_attempted_tests ?? [],
            dailyActivity: data.daily_activity ?? [],
            languagesPerTest: data.languages_per_test ?? [],
            failRateByVerdict: data.fail_rate_by_verdict ?? []
        });
    } catch (error) {
        console.error('Error in /api/analytics/tests:', error);
        res.status(500).json({ success: false, message: 'Server error' });
    }
});


app.get('/tables', async (req, res) => {
    try {
        const result = await pool.query('SELECT * FROM get_all_table_names()');
        res.json(result.rows);
    } catch (error) {
        console.error('Помилка при отриманні таблиць:', error);
        res.status(500).json({ error: 'Помилка при отриманні таблиць' });
    }
});

app.get('/tables/:table', async (req, res) => {
    const tableName = req.params.table;

    try {
        const result = await pool.query(`SELECT * FROM "${tableName}"`);
        res.json(result.rows);
    } catch (err) {
        console.error(`GET /tables/${tableName} error:`, err);
        res.status(500).json({ error: err.message });
    }
});

app.post('/:table', async (req, res) => {
    const { table } = req.params;
    const body = req.body;

    delete body.id;

    try {
        const columns = Object.keys(body).map(col => `"${col}"`).join(', ');
        const values = Object.values(body);
        const placeholders = values.map((_, i) => `$${i + 1}`).join(', ');

        const query = `INSERT INTO ${table} (${columns}) VALUES (${placeholders}) RETURNING *`;
        const { rows } = await pool.query(query, values);

        res.json(rows[0]);
    } catch (error) {
        console.error(`POST /api/tables/${table} error:`, error);
        res.status(500).json({ error: error.message });
    }
});

app.delete('/tables/:table', async (req, res) => {
    const { table } = req.params;

    try {
        await pool.query(`DROP TABLE IF EXISTS ${table} CASCADE`);
        res.json({ success: true, message: `Таблиця ${table} видалена.` });
    } catch (error) {
        console.error(`DELETE /api/tables/${table} error:`, error);
        res.status(500).json({ success: false, message: 'Помилка при видаленні таблиці.' });
    }
});

app.put('/:table/:id', async (req, res) => {
    const { table, id } = req.params;
    const data = req.body;

    try {
        const columns = Object.keys(data);
        const values = Object.values(data);

        const setClause = columns.map((col, i) => `${col} = $${i + 1}`).join(', ');
        const query = `UPDATE ${table} SET ${setClause} WHERE id = $${columns.length + 1}`;

        await pool.query(query, [...values, id]);

        res.json({ message: 'Успішно оновлено' });
    } catch (error) {
        console.error('Update error:', error);
        res.status(500).json({ error: 'Помилка оновлення' });
    }
});

app.delete('/:table/:id', async (req, res) => {
    const { table, id } = req.params;

    try {
        const query = `DELETE FROM ${table} WHERE id = $1`;
        await pool.query(query, [id]);

        res.json({ message: 'Запис успішно видалено' });
    } catch (error) {
        console.error('Delete error:', error);
        res.status(500).json({ error: 'Помилка видалення' });
    }
});


https.createServer(options, app).listen(3000, () => {
    console.log('HTTPS API працює на https://programing-olympiad.ddns.net:3000');
});