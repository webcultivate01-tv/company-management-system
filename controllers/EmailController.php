<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/EmailLog.php';
require_once __DIR__ . '/../config/mail.php';

use PHPMailer\PHPMailer\Exception;

class EmailController extends BaseController {
    private User     $userModel;
    private EmailLog $emailLogModel;

    public function __construct() {
        $this->userModel     = new User();
        $this->emailLogModel = new EmailLog();
    }

    public function index(): void {
        $this->requireRole('admin');
        $users = $this->userModel->getActiveUsers();
        $logs  = $this->emailLogModel->getRecent(30);
        $this->view('admin/email', ['users' => $users, 'logs' => $logs, 'flash' => $this->getFlash()]);
    }

    public function send(): void {
        $this->requireRole('admin');

        if (!$this->isPost()) { $this->redirect('admin/email'); }

        $subject   = $this->sanitize($this->post('subject'));
        $message   = strip_tags($this->post('message'), '<b><i><br><p><ul><li><strong><em>');
        $recipient = $this->post('recipient'); // 'all', user_id, or array of ids

        if (empty($subject) || empty($message)) {
            $this->setFlash('error', 'Subject and message are required');
            $this->redirect('admin/email');
        }

        $allUsers = $this->userModel->getActiveUsers();
        $targets  = [];

        if ($recipient === 'all') {
            $targets = $allUsers;
        } else {
            $ids = is_array($recipient) ? $recipient : [$recipient];
            foreach ($allUsers as $u) {
                if (in_array((string)$u['_id'], $ids)) {
                    $targets[] = $u;
                }
            }
        }

        if (empty($targets)) {
            $this->setFlash('error', 'No recipients selected');
            $this->redirect('admin/email');
        }

        $successCount = 0;
        $failCount    = 0;

        foreach ($targets as $user) {
            try {
                $mail = MailConfig::createMailer();
                $mail->addAddress($user['email'], $user['name']);
                $mail->Subject = $subject;
                $mail->Body    = $this->buildEmailBody($user['name'], $message);
                $mail->AltBody = strip_tags($message);
                $mail->send();
                $successCount++;
            } catch (Exception $e) {
                $failCount++;
                error_log('Mail Error: ' . $e->getMessage());
            }
        }

        // Log the email
        $recipientList = array_column($targets, 'email');
        $this->emailLogModel->log([
            'subject'    => $subject,
            'message'    => $message,
            'recipients' => $recipientList,
            'sentBy'     => $_SESSION['user_id'],
            'sentByName' => $_SESSION['name'],
            'success'    => $successCount,
            'failed'     => $failCount,
        ]);

        $this->setFlash('success', "Email sent to $successCount recipient(s)" . ($failCount ? " ($failCount failed)" : ''));
        $this->redirect('admin/email');
    }

    private function buildEmailBody(string $name, string $message): string {
        return "
        <div style='font-family:Arial,sans-serif;max-width:600px;margin:0 auto;padding:20px;'>
            <div style='background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);padding:30px;border-radius:10px 10px 0 0;'>
                <h1 style='color:white;margin:0;font-size:24px;'>CompanyMS</h1>
            </div>
            <div style='background:#f9f9f9;padding:30px;border-radius:0 0 10px 10px;border:1px solid #eee;'>
                <p style='color:#333;font-size:16px;'>Hi <strong>" . htmlspecialchars($name) . "</strong>,</p>
                <div style='color:#555;font-size:15px;line-height:1.6;'>$message</div>
                <hr style='border:none;border-top:1px solid #eee;margin:20px 0;'>
                <p style='color:#999;font-size:12px;'>This email was sent from CompanyMS. Please do not reply to this email.</p>
            </div>
        </div>";
    }
}
