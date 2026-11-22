import re

def check_file(path):
    print("Checking:", path)
    with open(path, "r", encoding="utf-8") as f:
        content = f.read()
    
    # Simple tag parser (ignoring PHP code)
    # Remove php tags first
    clean_content = re.sub(r'<\?php.*?\?>', '', content, flags=re.DOTALL)
    clean_content = re.sub(r'<\?=.*?\?>', '', clean_content, flags=re.DOTALL)
    
    # Find all HTML tags
    tags = re.findall(r'<(/?[a-zA-Z0-9:-]+)(?:\s+[^>]*?)?>', clean_content)
    
    stack = []
    void_tags = {'img', 'input', 'br', 'hr', 'meta', 'link'}
    
    for tag in tags:
        tag_name = tag.lower()
        if tag_name in void_tags:
            continue
        if tag_name.startswith('/'):
            name = tag_name[1:]
            if not stack:
                print(f"Error: closing tag </{name}> but stack is empty")
            else:
                top = stack.pop()
                if top != name:
                    print(f"Error: closing tag </{name}> does not match open tag <{top}>")
                    # Put it back to see what else happens
                    stack.append(top)
        else:
            stack.append(tag_name)
            
    if stack:
        print("Unclosed tags remaining on stack:", stack)
    else:
        print("All tags matched successfully!")

check_file("/opt/cakephp-app/painel/templates/Clientes/novo.php")
check_file("/opt/cakephp-app/painel/templates/Clientes/editar.php")
