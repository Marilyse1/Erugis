<?php

function generate_tokens(): array
{
    $selector = bin2hex(random_bytes(16));
    $validator = bin2hex(random_bytes(32));

    return [$selector, $validator, $selector . ':' . $validator];
}

function parse_token(string $token): ?array
{
    $parts = explode(':', $token);

    if ($parts && count($parts) == 2) {
        return [$parts[0], $parts[1]];
    }
    return null;
}

function insert_user_token(int $id_user, string $selector, string $hashed_validator, string $expiry): bool
{
    $sql = 'INSERT INTO user_tokens(id_user, selector, hashed_validator, expiry)
            VALUES(:id_user, :selector, :hashed_validator, :expiry)';

    $statement = db()->prepare($sql);
    $statement->bindValue(':id_user', $id_user);
    $statement->bindValue(':selector', $selector);
    $statement->bindValue(':hashed_validator', $hashed_validator);
    $statement->bindValue(':expiry', $expiry);

    return $statement->execute();
}

function find_user_token_by_selector(string $selector)
{

    $sql = 'SELECT id, selector, hashed_validator, id_user, expiry
                FROM user_tokens
                WHERE selector = :selector AND
                    expiry >= now()
                LIMIT 1';

    $statement = db()->prepare($sql);
    $statement->bindValue(':selector', $selector);

    $statement->execute();

    return $statement->fetch(PDO::FETCH_ASSOC);
}

function delete_user_token(int $id_user): bool
{
    $sql = 'DELETE FROM user_tokens WHERE id_user = :id_user';
    $statement = db()->prepare($sql);
    $statement->bindValue(':id_user', $id_user);

    return $statement->execute();
}

function find_user_by_token(string $token)
{
    $tokens = parse_token($token);

    if (!$tokens) {
        return null;
    }

    $sql = 'SELECT u.id_user, email
            FROM users u
            INNER JOIN user_tokens t ON u.id_user = t.id_user
            WHERE selector = :selector AND
                expiry > now()
            LIMIT 1';

    $statement = db()->prepare($sql);
    $statement->bindValue(':selector', $tokens[0]);
    $statement->execute();

    return $statement->fetch(PDO::FETCH_ASSOC);
}

function token_is_valid(string $token): bool 
{ 
    // analyser le jeton pour obtenir le sélecteur et le validateur 
    [$selector, $validator] = parse_token($token);

    $tokens = find_user_token_by_selector($selector);
    if (!$tokens) {
        return false;
    }
    
    return password_verify($validator, $tokens['hashed_validator']);
} 

function remember_me(int $id_user, int $day = 30)
{
    [$selector, $validator, $token] = generate_tokens();

    // remove all existing token associated with the user id
    delete_user_token($id_user);

    // set expiration date
    $expired_seconds = time() + 60 * 60 * 24 * $day;

    // insert a token to the database
    $hash_validator = password_hash($validator, PASSWORD_DEFAULT);
    $expiry = date('Y-m-d H:i:s', $expired_seconds);

    if (insert_user_token($id_user, $selector, $hash_validator, $expiry)) {
        setcookie('remember_me', $token, $expired_seconds);
    }
}
?>