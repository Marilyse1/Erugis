<?php
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

function delete_user_token(int $id_user): bool
{
    $sql = 'DELETE FROM user_tokens WHERE id_user = :id_user';
    $statement = db()->prepare($sql);
    $statement->bindValue(':id_user', $id_user);

    return $statement->execute();
}
?>