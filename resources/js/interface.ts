type Omit<T, K extends keyof T> = Pick<T, Exclude<keyof T, K>>;
type PartialBy<T, K extends keyof T> = Omit<T, K> & Partial<Pick<T, K>>;

export interface AppFile {
  id: string;
  name: string;
  labels: string[];
  created_at: Date;
  size: string;
}

export interface AppFolder {
  id: string;
  name: string;
  nodes: AppFolder[];
  created_at: Date;
}

export interface TreeNode {
  id: string;
  name: string;
  nodes: TreeNode[];
}
